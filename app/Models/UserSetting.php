<?php

namespace App\Models;

use App\Collections\UserSettingsCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $setting
 * @property int $user_id
 */
class UserSetting extends Model
{
    use HasFactory;

    public const string NO_RAFFLE_ENTRY = 'no_raffle_entry';
    protected $fillable = [
        'setting',
        'user_id',
    ];

    public const array SETTINGS_MAP = [
        self::NO_RAFFLE_ENTRY => 'Не брати участь в розіграшах'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSetting(): string
    {
        return $this->setting;
    }

    public function getSettingName(): string
    {
        return self::SETTINGS_MAP[$this->setting] ?? 'Помилка. Налаштування не існує.';
    }

    public function setSetting(string $setting): void
    {
        $this->setting = $setting;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getSettingsMap(): array
    {
        return self::SETTINGS_MAP;
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array<int, Model>  $models
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function newCollection(array $models = []): Collection
    {
        return new UserSettingsCollection($models);
    }
}
