<?php

namespace App\Models;

use App\Collections\DonateCollection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property int $volunteer_id
 * @property Volunteer $volunteer
 * @property float $amount
 * @property string $uniq_hash
 * @property Carbon $validated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Donate extends Model
{
    use SoftDeletes, HasFactory;

    protected $with = ['volunteer'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'volunteer_id',
        'uniq_hash',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function volunteer(): HasOne
    {
        return $this->hasOne(Volunteer::class, 'id', 'volunteer_id');
    }

    public function donater(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getType(): string
    {
        return $this->volunteerId;
    }

    public function getHumanType(): string
    {
        return $this->volunteer->getName();
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUniqHash(): string
    {
        return $this->uniq_hash;
    }

    public function setUniqHash(string $uniq_hash): self
    {
        $this->uniq_hash = $uniq_hash;

        return $this;
    }

    public function getValidatedAt(): ?Carbon
    {
        return $this->validated_at;
    }

    /**
     * @param Carbon|null $validatedAt
     * @return $this
     */
    public function setValidatedAt(Carbon $validatedAt = null): self
    {
        $this->validated_at = $validatedAt;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVolunteerId(): int
    {
        return $this->volunteer_id;
    }

    public function getVolunteer(): Volunteer
    {
        return $this->volunteer;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function isValidated(): bool
    {
        return (bool)$this->getValidatedAt();
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array<int, Model>  $models
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function newCollection(array $models = []): Collection
    {
        return new DonateCollection($models);
    }
}
