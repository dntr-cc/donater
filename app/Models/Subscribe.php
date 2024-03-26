<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $user_id
 * @property int $volunteer_id
 * @property User $donater
 * @property User $volunteer
 * @property float $amount
 * @property string $frequency
 * @property Carbon $first_message_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property SubscribesMessage|null $subscribes
 */
class Subscribe extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'volunteer_id',
        'amount',
        'scheduled_at',
        'first_message_at',
        'frequency',
    ];

    protected $casts = [
        'first_message_at' => 'datetime',
    ];

    public function donater(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function volunteer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'volunteer_id');
    }

    public function subscribes(): HasOne
    {
        return $this->hasOne(SubscribesMessage::class, 'subscribes_id', 'id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): Subscribe
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getVolunteerId(): int
    {
        return $this->volunteer_id;
    }

    public function setVolunteerId(int $volunteer_id): Subscribe
    {
        $this->volunteer_id = $volunteer_id;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Subscribe
    {
        $this->amount = $amount;
        return $this;
    }

    public function getScheduledAt(): string
    {
        return $this->scheduled_at;
    }

    public function setScheduledAt(Carbon $scheduled_at): Subscribe
    {
        $this->scheduled_at = $scheduled_at;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deleted_at;
    }

    public function getFrequency(): string
    {
        return $this->frequency;
    }

    public function getFirstMessageAt(): Carbon
    {
        return $this->first_message_at;
    }

    public function getVolunteer(): User
    {
        return self::with('volunteer')->where('id', '=', $this->getId())->first()->volunteer;
    }

    public function getDonater(): ?User
    {
        return self::with('donater')->where('id', '=', $this->getId())->first()->donater;
    }

    public function getNextSubscribesMessage(): ?SubscribesMessage
    {
        return self::with('subscribes')->where('id', '=', $this->getId())->first()->subscribes;
    }

    public function getModifier(string $frequency): string
    {
        $days = (int)date('t');

        return match (true) {
            $frequency === SubscribesMessage::EACH_2_DAYS_NAME => SubscribesMessage::EACH_2_DAYS_VALUE,
            $frequency === SubscribesMessage::EACH_3_DAYS_NAME => SubscribesMessage::EACH_3_DAYS_VALUE,
            $frequency === SubscribesMessage::WEEKLY_NAME => SubscribesMessage::WEEKLY_VALUE,
            $frequency === SubscribesMessage::MONTHLY_NAME => SubscribesMessage::MONTHLY_VALUE,
            $frequency === SubscribesMessage::BIWEEKLY_DAYS_NAME => match ($days) {
                31     => '+16 days',
                30, 29 => '+15 days',
                28 => '+14 days',
            },
            default => SubscribesMessage::DAILY_VALUE,
        };
    }
}
