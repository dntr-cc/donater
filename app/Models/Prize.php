<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property array $available_for
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Carbon $started_at
 */
class Prize extends Model
{
    use SoftDeletes, HasFactory;

    public const array AVAILABLE_TYPES = [
        self::ONLY_FOR_ME      => 'Тільки для моїх зборів',
        self::FOR_ALL          => 'Для всіх волонтерів',
//        self::FOR_CHOSEN_USERS => 'Тільки для обраних волонтерів',
    ];
    public const string ONLY_FOR_ME = 'only_for_me';
    public const string FOR_ALL = 'for_all';
    public const string FOR_CHOSEN_USERS = 'for_chosen_users';
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
        'available_for',
    ];

    protected $casts = [
        'available_for' => 'array',
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

    public function getAvailableFor(): array
    {
        return $this->available_for;
    }

    public function setAvailableFor(array $available_for): Prize
    {
        $this->available_for = $available_for;

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

    public function getDonater(): User
    {
        return self::with('donater')->where('id', '=', $this->getId())->first()->donater;
    }
}
