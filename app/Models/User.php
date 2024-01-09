<?php

namespace App\Models;

use App\Collections\UserSettingsCollection;
use App\Services\UserCodeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $username
 * @property int $telegram_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $avatar
 * @property bool $is_premium
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|Donate[] $donates
 * @property Collection|Fundraising[] $fundraisings
 * @property Collection|Prize[] $prizes
 * @property Collection|UserLink[] $links
 * @property UserSettingsCollection|UserSetting[] $settings
 * @property int $approved_donates_count
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $with = ['donates', 'fundraisings', 'links', 'settings', 'prizes'];

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
    ];

    protected static function boot(): void
    {
        parent::boot();
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
    public function settings(): HasMany
    {
        return $this->hasMany(UserSetting::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function getAtUsername(): string
    {
        return $this->username ? '@' . $this->username : '';
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
     * @param mixed $username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return int
     */
    public function getTelegramId(): int
    {
        return $this->telegram_id;
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
    public function getFullName(): string
    {
        return trim("{$this->getFirstName()} {$this->getLastName()}");
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function getNotValidatedDonatesCount(): int
    {
        return $this->getDonates()->count() - $this->getDonateCount();
    }

    public function getDonates(): Collection|array
    {
        return $this->donates;
    }

    public function getFundraisings(): Collection|array
    {
        return $this->fundraisings;
    }

    public function getDonateCount(): int
    {
        return $this->donates->count();
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
            <a href="{$this->getUserLink()}" class="">
                {$this->getFullName()} ({$this->getAtUsername()})
            </a>
            HTML;
    }

    public function withPrizes(): self
    {
        return self::with('prizes')->where('id', '=', $this->getId())->first();
    }
}
