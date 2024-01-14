<?php

namespace App\Models;

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
 * @property string $name
 * @property string $description
 * @property string $avatar
 * @property int $fundraising_id
 * @property Fundraising|null $fundraising
 * @property int $user_id
 * @property User $donater
 * @property string $raffle_type
 * @property int $raffle_winners
 * @property float $raffle_price
 * @property string $available_type
 * @property string $available_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Carbon $started_at
 * @property bool $is_enabled
 */
class Prize extends Model
{
    use SoftDeletes, HasFactory;

    public const array AVAILABLE_TYPES = [
        self::ONLY_FOR_ME               => 'Тільки для моїх зборів',
        self::FOR_ALL                   => 'Для всіх волонтерів',
        self::FOR_SUBSCRIBED_VOLUNTEERS => 'Тільки для моїх волонтерів (підписка)',
    ];
    public const string ONLY_FOR_ME = 'only_for_me';
    public const string FOR_ALL = 'for_all';
    public const string FOR_SUBSCRIBED_VOLUNTEERS = 'for_subscribed_volunteers';
    public const string STATUS_NEW = 'new';
    public const string STATUS_WAITING = 'waiting';
    public const string STATUS_GRANTED = 'granted';
    protected $fillable = [
        'name',
        'description',
        'avatar',
        'fundraising_id',
        'user_id',
        'raffle_type',
        'raffle_winners',
        'raffle_price',
        'available_type',
        'available_status',
        'is_enabled',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    public function fundraising(): HasOne
    {
        return $this->hasOne(Fundraising::class, 'id', 'fundraising_id');
    }

    public function donater(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class, 'prize_id', 'id');
    }

    public function getDonater(): User
    {
        return self::with('donater')->where('id', '=', $this->getId())->first()->donater;
    }

    public function getVolunteer(): User
    {
        return self::with('fundraising')->where('id', '=', $this->getId())->first()->fundraising->getVolunteer();
    }

    /**
     * @return Collection|Winner[]
     */
    public function getWinners(): Collection
    {
        return Winner::query()->where('prize_id', '=', $this->getId())->get();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Prize
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Prize
    {
        $this->description = $description;

        return $this;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): Prize
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getFundraisingId(): ?int
    {
        return $this->fundraising_id;
    }

    public function setFundraisingId(int $fundraisingId = null): Prize
    {
        if (null === $fundraisingId && !$this->isEnabled() && self::STATUS_WAITING === $this->getAvailableStatus()) {
            return $this;
        }
        $this->fundraising_id = $fundraisingId;

        return $this;
    }

    public function getFundraising(): Fundraising
    {
        return $this->fundraising;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): Prize
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getRaffleType(): string
    {
        return $this->raffle_type;
    }

    public function setRaffleType(string $raffleType): Prize
    {
        $this->raffle_type = $raffleType;

        return $this;
    }

    public function getRaffleWinners(): int
    {
        return $this->raffle_winners;
    }

    public function setRaffleWinners(int $raffleWinners): Prize
    {
        $this->raffle_winners = $raffleWinners;

        return $this;
    }

    public function getRafflePrice(): float
    {
        return $this->raffle_price;
    }

    public function setRafflePrice(float $rafflePrice): Prize
    {
        $this->raffle_price = $rafflePrice;

        return $this;
    }

    public function getAvailableType(): string
    {
        return $this->available_type;
    }

    public function setAvailableType(string $available_type): Prize
    {
        $this->available_type = $available_type;

        return $this;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function setCreatedAt($value): Prize
    {
        $this->created_at = $value;

        return $this;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($value): Prize
    {
        $this->updated_at = $value;

        return $this;
    }

    public function getDeletedAt(): Carbon
    {
        return $this->deleted_at;
    }

    public function setDeletedAt($value): Prize
    {
        $this->deleted_at = $value;

        return $this;
    }

    public function getStartedAt(): Carbon
    {
        return $this->started_at;
    }

    public function setStartedAt($value): Prize
    {
        $this->started_at = $value;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function setEnabled(bool $isEnabled): Prize
    {
        $this->is_enabled = $isEnabled;

        return $this;
    }

    public function getAvailableStatus(): string
    {
        return $this->available_status;
    }

    public function isNeedApprove(): bool
    {
        return self::STATUS_WAITING === $this->getAvailableStatus();
    }

    public function setAvailableStatus(string $availableStatus): Prize
    {
        $this->available_status = $availableStatus;

        return $this;
    }
}
