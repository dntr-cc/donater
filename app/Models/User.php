<?php

namespace App\Models;

use App\Collections\DonateCollection;
use App\Collections\UserSettingsCollection;
use App\Services\UserCodeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

/**
 * @property int $id
 * @property string $username
 * @property int $telegram_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $avatar
 * @property bool $is_premium
 * @property bool $forget
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property DonateCollection|Donate[] $donates
 * @property Collection|Fundraising[] $fundraisings
 * @property Collection|Prize[] $prizes
 * @property Collection|UserLink[] $links
 * @property Collection|DeepLink[] $deep
 * @property UserSettingsCollection|UserSetting[] $settings
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const array ESCAPE_MAP = [
        '_' => '\_',
        '*' => '\*',
        '[' => '\[',
        ']' => '\]',
        '(' => '\(',
        ')' => '\)',
        '~' => '\~',
//        '`' => '\`',
        '>' => '\>',
        '#' => '\#',
        '+' => '\+',
        '-' => '\-',
        '=' => '\=',
        '|' => '\|',
        '{' => '\{',
        '}' => '\}',
        '.' => '\.',
        '!' => '\!',
    ];
    protected $with = ['fundraisings', 'links', 'settings', 'deep'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'telegram_id',
        'first_name',
        'last_name',
        'avatar',
        'is_premium',
        'forget',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(static function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    /**
     * @return HasMany
     */
    public function donates(): HasMany
    {
        return $this->hasMany(Donate::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function fundraisings(): HasMany
    {
        return $this->hasMany(Fundraising::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(UserLink::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function deep(): HasMany
    {
        return $this->hasMany(DeepLink::class, 'volunteer_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->hasMany(UserSetting::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function refs(): HasMany
    {
        return $this->hasMany(Referral::class, 'user_id', 'id');
    }

    /**
     * @param mixed $username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param int $telegramId
     * @return User
     */
    public function setTelegramId(int $telegramId): self
    {
        $this->telegram_id = $telegramId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsernameWithFullName(): string
    {
        return trim("{$this->getFirstName()} {$this->getLastName()} ({$this->getAtUsername()})");
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return (string)$this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return (string)$this->last_name;
    }

    /**
     * @return mixed
     */
    public function getAtUsername(): string
    {
        return $this->username ? '@' . $this->username : '';
    }

    /**
     * @param string|null $firstName
     * @return User
     */
    public function setFirstName(?string $firstName): self
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * @param string|null $lastName
     * @return User
     */
    public function setLastName(?string $lastName): self
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPremium(): bool
    {
        return $this->is_premium;
    }

    /**
     * @param bool $isPremium
     * @return User
     */
    public function setIsPremium(bool $isPremium): self
    {
        $this->is_premium = $isPremium;

        return $this;
    }

    /**
     * @return bool
     */
    public function isForget(): bool
    {
        return (bool)$this->forget;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return (string)$this->avatar;
    }

    /**
     * @param string|null $avatar
     * @return $this
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at->setTimezone(config('app.timezone'));
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function getDonateCount(): int
    {
        return $this->getDonates()->count();
    }

    public function getDonates(): Collection|DonateCollection|array
    {
        return self::with('donates')->where('id', '=', $this->getId())->first()?->donates;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getPrizesCount(): int
    {
        return $this->prizes->count();
    }

    /**
     * @return Collection|UserLink[]
     */
    public function getLinks(): Collection|array
    {
        return $this->links;
    }

    /**
     * @return Collection|DeepLink[]
     */
    public function getDeepLinks(): Collection|array
    {
        return $this->deep ?? new Collection();
    }

    public function isSuperAdmin(): bool
    {
        return 1 === $this->getId();
    }

    public function getUserCode(): string
    {
        return app(UserCodeService::class)->getUserDonateCode($this->getId());
    }

    public function getUserHref(): string
    {
        return <<<HTML
            <a href="{$this->getUserLink()}">
                {$this->getFullName()} ({$this->getAtUsername()})
            </a>
            HTML;
    }

    /**
     * @return string
     */
    public function getUserLink(): string
    {
        return url('/u/' . $this->getUsername());
    }

    /**
     * @return mixed
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return trim("{$this->getFirstName()} {$this->getLastName()}");
    }

    public function withPrizes(): self
    {
        return self::without(['fundraisings', 'links', 'settings'])->with('prizes')->where('id', '=', $this->getId())->first();
    }

    public function getSubscribe(int $userId): ?Subscribe
    {
        return Subscribe::query()->where('volunteer_id', '=', $this->getId())
            ->where('user_id', '=', $userId)->first();
    }

    /**
     * @return Collection|Subscribe[]
     */
    public function getSubscribers(): Collection
    {
        return Subscribe::query()->where('volunteer_id', '=', $this->getId())->get();
    }

    public function getSubscribersAsSubscriber(): Collection
    {
        return Subscribe::query()->where('user_id', '=', $this->getId())->get();
    }

    /**
     * @param int $userId
     * @return Collection|Subscribe[]
     */
    public function getSubscribes(): Collection
    {
        return Subscribe::query()->where('user_id', '=', $this->getId())->get();
    }

    public function sendBotMessage(string $message, bool $throw = false): void
    {
        if (str_contains(config('app.url'), 'localhost')) {
            Log::critical($message);
            return;
        }
        try {
            Telegram::sendMessage([
                'chat_id'    => $this->getTelegramId(),
                'text'       => strtr($message, self::ESCAPE_MAP),
                'parse_mode' => 'MarkdownV2',
            ]);
        } catch (Throwable $throwable) {
            if (str_contains($throwable->getMessage(), 'bot was blocked by the user')) {
                $this->update(['forget' => true]);
            }
            Log::error($throwable->getMessage(), ['code' => $throwable->getCode(), 'trace' => $throwable->getTraceAsString()]);
            if ($throw) {
                throw $throwable;
            }
        }
    }

    /**
     * @return int
     */
    public function getTelegramId(): int
    {
        return $this->telegram_id;
    }

    public function getRandomFundraising(): ?Fundraising
    {
        if ($this->fundraisings->isEmpty()) {
            return null;
        }
        $fundraisings = $this->getFundraisings()->filter(fn(Fundraising $fundraising) => $fundraising->isEnabled());
        if ($fundraisings->isEmpty()) {
            return null;
        }


        return $fundraisings->random();
    }

    public function getFundraisings(): Collection|array
    {
        return $this->fundraisings;
    }

    /**
     * @return float
     * @uses Donate
     */
    public function getDonatesSumAll(): float
    {
        return DB::query()
            ->select(DB::raw('sum(amount) as amount'))
            ->from('donates') // @use Donate::class
            ->where('user_id', '=', $this->getId())
            ->orderBy('amount', 'desc')
            ->get()->first()?->amount ?? 0.00;
    }

    /**
     * @return array
     * @uses Donate
     */
    public function getDonatesByVolunteer(): array
    {
        return DB::query()
            ->select([DB::raw('sum(donates.amount) as amount'), DB::raw('count(donates.amount) as count'), 'fundraisings.user_id as volunteer_id'])
            ->from('donates') // @use Donate::class
            ->join('fundraisings', 'fundraisings.id', '=', 'donates.fundraising_id') // @use Donate::class
            ->where('donates.user_id', '=', $this->getId())
            ->groupBy(['fundraisings.user_id'])
            ->orderBy('amount', 'desc')
            ->get()->toArray() ?? [];
    }

    public function hasFundraisings(): int
    {
        return $this->fundraisings->count() ? 1 : 0;
    }
}
