<?php

namespace App\Models;

use App\Collections\DonateCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'key';
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('donates_count', static function (Builder $builder) {
            $builder->withCount('donates');
        });
        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->withCount('donates')->orderBy('is_enabled', 'desc')->orderBy('id', 'desc');
        });
    }

    /**
     * @return Collection|self[]
     */
    public static function getActual(): Collection
    {
        return static::query()->where('is_enabled', '=', true)->get();
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

    public function getId(): int
    {
        return $this->id;
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

    public function getJarLink(bool $withCode = true): string
    {
        if ($withCode && $user = auth()?->user()) {
            return $this->link . '?' . 't=' . $user->getUserCode();
        }

        return $this->link;
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

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function setEnabled(bool $isEnabled): self
    {
        $this->is_enabled = $isEnabled;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
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

    public function getSpreadsheetId(): string
    {
        return $this->spreadsheet_id;
    }

    public function getSpreadsheetLink(): string
    {
        return strtr('https://docs.google.com/spreadsheets/d/:spreadsheetId/edit#gid=0', [':spreadsheetId' => $this->getSpreadsheetId()]);
    }

    public function setSpreadsheetId(string $spreadsheetId): self
    {
        $this->spreadsheet_id = $spreadsheetId;

        return $this;
    }

    public function getDonates(): ?DonateCollection
    {
        return $this->donates;
    }

    public function getDonateCollection(): ?DonateCollection
    {
        return self::with('donates')->where('id', '=', $this->getId())->first()->donates;
    }

    /**
     * @return Collection|Prize[]|null
     */
    public function getPrizes(): ?Collection
    {
        return self::with('prizes')->where('id', '=', $this->getId())->first()->prizes;
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

    public function rafflesPredictCollection(): \App\Collections\RaffleUserCollection
    {
        return $this->getDonateCollection()->getRaffleUserCollection(
            UserSetting::query()
                ->where('setting', '=', UserSetting::NO_RAFFLE_ENTRY)
                ->get()
                ->pluck('user_id')
                ->toArray()
        );
    }

    public static function getRandom(): self
    {
        return self::query()->where('is_enabled', '=', 'true')->get()->random();
    }

    public function getVolunteer(): User
    {
        return self::with('volunteer')->where('id', '=', $this->getId())->first()->volunteer;
    }
}
