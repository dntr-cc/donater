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
    public const string USE_PERCENT_INSTEAD_FRACTION = 'use_percent_instead_fraction';
    public const string DONT_SEND_SUBSCRIBERS_INFORMATION = 'dont_send_subscribers_information';
    public const string DONT_SEND_MARKETING_MESSAGES = 'dont_send_marketing_messages';
    public const string USE_FEMININE_FORMS_WHEN_MY_ROLE_IS_CALLED = 'USE_FEMININE_FORMS_WHEN_MY_ROLE_IS_CALLED';
    protected $fillable = [
        'setting',
        'user_id',
    ];

    public const array SETTINGS_MAP = [
        self::NO_RAFFLE_ENTRY                           => 'Не брати участь в розіграшах',
        self::USE_PERCENT_INSTEAD_FRACTION              => 'Показувати відсотки замість дробі в шансах розіграшів',
        self::DONT_SEND_MARKETING_MESSAGES              => 'Не отримувати повідомлення маркетингових нагадувань чи розсилок',
        self::DONT_SEND_SUBSCRIBERS_INFORMATION         => 'Як волонтер: не отримувати повідомлення про додавання/видалення/зміни підписок серійних донатерів',
        self::USE_FEMININE_FORMS_WHEN_MY_ROLE_IS_CALLED => 'Використовувати фемінітиви, коли описують мою роль (волонтерка/донатерка)',
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
     * @param array<int, Model> $models
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function newCollection(array $models = []): Collection
    {
        return new UserSettingsCollection($models);
    }
}
