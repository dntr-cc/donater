<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $subscribes_id
 * @property string $frequency
 * @property bool $has_open_fundraisings
 * @property Carbon $scheduled_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SubscribesMessage extends Model
{
    use HasFactory;

    public const string DAILY_VALUE = '+1 day';
    public const string DAILY_NAME = 'daily';
    public const string EACH_2_DAYS_VALUE = '+2 day';
    public const string EACH_2_DAYS_NAME = 'each_2_days';
    public const string EACH_3_DAYS_VALUE = '+3 day';
    public const string EACH_3_DAYS_NAME = 'each_3_days';
    public const string WEEKLY_VALUE = '+7 day';
    public const string WEEKLY_NAME = 'weekly';
    public const string BIWEEKLY_DAYS_NAME = 'biweekly';
    public const string MONTHLY_VALUE = '+1 month';
    public const string MONTHLY_NAME = 'monthly';
    public const array FREQUENCY_NAME_MAP = [
        self::DAILY_NAME         => 'Щоденно',
        self::EACH_2_DAYS_NAME   => 'Кожні два дні',
        self::EACH_3_DAYS_NAME   => 'Кожні три дні',
        self::WEEKLY_NAME        => 'Раз на тиждень',
        self::BIWEEKLY_DAYS_NAME => 'Два рази на місяць',
        self::MONTHLY_NAME       => 'Раз на місяць',
    ];

    protected $fillable = [
        'subscribes_id',
        'frequency',
        'scheduled_at',
        'has_open_fundraisings',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getSubscribesId(): int
    {
        return $this->subscribes_id;
    }
    public function getFrequency(): string
    {
        return $this->frequency;
    }
    public function getScheduledAt(): Carbon
    {
        return $this->scheduled_at;
    }
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function isHasOpenFundraisings(): bool
    {
        return $this->has_open_fundraisings;
    }
}
