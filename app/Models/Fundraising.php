<?php

namespace App\Models;

use App\Collections\DonateCollection;
use App\Collections\RaffleUserCollection;
use App\Collections\RowCollection;
use App\Services\FundraisingShortCodeService;
use App\Services\GoogleServiceSheets;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $link
 * @property string $page
 * @property string $avatar
 * @property string $description
 * @property string $spreadsheet_id
 * @property bool $is_enabled
 * @property bool $forget
 * @property int $user_id
 * @property FundraisingDetail|null $details
 * @property User|null $volunteer
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property DonateCollection|null $donates
 * @property Collection|null $prizes
 * @property Collection|null $booked_prizes
 */
class Fundraising extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'key',
        'name',
        'link',
        'page',
        'avatar',
        'description',
        'is_enabled',
        'user_id',
        'spreadsheet_id',
        'forget',
    ];

    /**
     * @return Collection|self[]
     */
    public static function active(): Collection
    {
        return static::all();
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('details', static function (Builder $builder) {
            $builder->with('details');
        });
        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->orderBy('is_enabled', 'desc')->orderBy('id', 'desc');
        });
        static::deleted(static function(self $fundraising) {
            $fundraising->details()->first()?->delete();
        });
        static::forceDeleted(static function(self $fundraising) {
            $fundraising->details()->withTrashed()->first()?->forceDelete();
        });
        static::restored(static function(self $fundraising) {
            $fundraising->details()->withTrashed()?->restore();
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'key';
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * @return null|Carbon
     */
    public function getDeletedAt(): ?Carbon
    {
        return $this->deleted_at;
    }


    /**
     * @return HasMany
     */
    public function donates(): HasMany
    {
        return $this->hasMany(Donate::class, 'fundraising_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class, 'fundraising_id', 'id')
            ->where('available_status', '=', Prize::STATUS_GRANTED);
    }

    /**
     * @return HasMany
     */
    public function booked_prizes(): HasMany
    {
        return $this->hasMany(Prize::class, 'fundraising_id', 'id')
            ->where('available_status', '=', Prize::STATUS_WAITING);
    }

    /**
     * @return HasOne|User
     */
    public function volunteer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne|FundraisingDetail
     */
    public function details(): HasOne
    {
        return $this->hasOne(FundraisingDetail::class, 'id', 'id');
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getJarLink(bool $withCode = true, int $withAmount = null, string $addComment = null): string
    {
        $needAddText = true;
        $getParams = '';
        if ($withAmount) {
            $getParams .= 'a=' . $withAmount . '&';
        }
        if ($withCode && $user = auth()?->user()) {
            $text = $user->getUserCode();
            if ($addComment) {
                $text .= ' ' . $addComment;
                $needAddText = false;
            }
            $getParams .= 't=' . $text;
        }
        if ($addComment && $needAddText) {
            $getParams .= 't=' . $addComment;
        }

        return $getParams ? $this->link . '?' . $getParams : $this->link;
    }

    public function getMonoRequest($link): string
    {
        return <<<HTML
        Добрий день. Мені треба виписки по банці для сайту donater.com.ua

        - $link

        Формат виписки
        - українською мовою
        - за весь період
        - в форматі xls/xlsx, таблиця, PDF не підходить
        - колонки: Дата та час, Категорія(разове поповнення чи за посиланням), Опис (від платника, або нарахування відсотків тощо), Коментар (платника), Сума (платежу), Валюта, Залишок на банці. САМЕ В ТАКОМУ ПОРЯДКУ, ЦЕ ВАЖЛИВО
        - формат дати: 31.12.2023 23:59:59

        Яка виписка не підійде, та придеться робити ще раз:
        - pdf формат (не пропонуйте пдф, будь ласка)
        - колонки в іншому порядку (це важливо)
        - коментарі до платежів не співпадають з реальними коментарями до платежів (таке було у багатьох наших волонтерів, це було багато разів)
        - формат дати відрізняється від того, що в запиті

        Буду очікувати на пошту/мессенджер/за посиланням.

        Дякую.
        HTML;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPage(): string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function setEnabled(bool $isEnabled): self
    {
        $this->is_enabled = $isEnabled;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSpreadsheetLink(): string
    {
        return strtr('https://docs.google.com/spreadsheets/d/:spreadsheetId/edit#gid=0', [':spreadsheetId' => $this->getSpreadsheetId()]);
    }

    public function getSpreadsheetId(): string
    {
        return $this->spreadsheet_id;
    }

    public function setSpreadsheetId(string $spreadsheetId): self
    {
        $this->spreadsheet_id = $spreadsheetId;

        return $this;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at->setTimezone(config('app.timezone'));
    }

    public function getDonates(): ?DonateCollection
    {
        return $this->donates;
    }

    /**
     * @return Collection|Prize[]|null
     */
    public function getPrizes(): ?Collection
    {
        return self::with('prizes')->where('id', '=', $this->getId())->first()->prizes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|Prize[]|null
     */
    public function getBookedPrizes(): ?Collection
    {
        return self::with('booked_prizes')->where('id', '=', $this->getId())->first()->booked_prizes;
    }

    /**
     * @return Collection|Prize[]|null
     */
    public function getAvailablePrizes(): ?Collection
    {
        $userPrizes = $this->getUserPrizes();
        $fundraiserPrizes = $this->getFundraiserPrizes();
        $availablePrizes = $this->getAvailablePrizesForAll();
        $availablePrizesSubscribers = $this->getAvailablePrizesForMe();

        return $availablePrizesSubscribers->merge($availablePrizes->merge($userPrizes->merge($fundraiserPrizes->all())->all())->all());
    }

    private function getUserPrizes(): Collection
    {
        return Prize::query()
            ->whereNull('fundraising_id')
            ->where('user_id', '=', auth()?->user()?->getId() ?? 0)
            ->where('available_type', '=', Prize::ONLY_FOR_ME)
            ->where('available_status', '=', Prize::STATUS_NEW)
            ->where('is_enabled', '=', true)
            ->get();
    }

    private function getFundraiserPrizes(): Collection
    {
        return Prize::query()
            ->whereNull('fundraising_id')
            ->where('user_id', '=', $this->getUserId())
            ->where('available_type', '=', Prize::ONLY_FOR_ME)
            ->where('available_status', '=', Prize::STATUS_NEW)
            ->where('is_enabled', '=', true)
            ->get();
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    private function getAvailablePrizesForAll(): Collection
    {
        return Prize::query()
            ->whereNull('fundraising_id')
            ->where('user_id', '!=', $this->getUserId())
            ->where('available_type', '=', Prize::FOR_ALL)
            ->where('available_status', '=', Prize::STATUS_NEW)
            ->where('is_enabled', '=', true)
            ->get();
    }

    private function getAvailablePrizesForMe(): Collection
    {
        return Prize::query()
            ->whereNull('fundraising_id')
            ->whereIn('user_id', Subscribe::query()->where('volunteer_id', '=', $this->getUserId())->get()->pluck('user_id')->toArray())
            ->where('available_type', '=', Prize::FOR_SUBSCRIBED_VOLUNTEERS)
            ->where('available_status', '=', Prize::STATUS_NEW)
            ->where('is_enabled', '=', true)
            ->get();
    }

    public function rafflesPredictCollection(): RaffleUserCollection
    {
        return $this->getDonateCollection()->getRaffleUserCollection(
            UserSetting::query()
                ->where('setting', '=', UserSetting::NO_RAFFLE_ENTRY)
                ->get()
                ->pluck('user_id')
                ->toArray()
        );
    }

    public function getDonateCollection(): ?DonateCollection
    {
        return self::with('donates')->where('id', '=', $this->getId())->first()->donates;
    }

    public function getVolunteer(): ?User
    {
        return self::with('volunteer')->where('id', '=', $this->getId())->first()->volunteer;
    }

    public function getShortLink(): string
    {
        return app(FundraisingShortCodeService::class)->getShortLink($this->getId());
    }

    public function getClassByState(): string
    {
        if ($this->isEnabled()) {
            return 'bg-primary-subtle';
        } elseif ($this->donates->count()) {
            return 'bg-success-subtle';
        }

        return 'bg-secondary-subtle';
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    /**
     * @return bool
     */
    public function isForget(): bool
    {
        return $this->forget;
    }

    /**
     * @return FundraisingDetail|null
     */
    public function getDetails(): ?FundraisingDetail
    {
        return $this->details;
    }

    public static function getAllRows(): RowCollection
    {
        $rows = new RowCollection();
        $service = app(GoogleServiceSheets::class);
        foreach (Fundraising::all() as $fundraising) {
            try {
                $rows->push(...$service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId())->all());
            } catch (Throwable $throwable) {
                Log::critical($throwable->getMessage(), ['fundraising' => $fundraising->toArray(), 'trace' => $throwable->getTraceAsString()]);
            }
        }

        return $rows;
    }
}
