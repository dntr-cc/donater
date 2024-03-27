<?php

namespace App\Models;

use App\Services\UserCodeService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

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
 * @property Collection|UserLink[] $links
 * @property int $approved_donates_count
 */
class User extends Authenticatable
{
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

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'username';
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
        return $this->created_at->setTimezone(config('app.timezone'));
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function isSuperAdmin(): bool
    {
        return 1 === $this->getId();
    }

    public function getUserCode(): string
    {
        return app(UserCodeService::class)->getUserDonateCode($this->getId());
    }
}
