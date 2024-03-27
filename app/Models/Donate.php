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
 * @property int $fundraising_id
 * @property Fundraising $fundraising
 * @property float $amount
 * @property string $hash
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Donate extends Model
{
    use HasFactory;

    protected $with = ['fundraising'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'fundraising_id',
        'hash',
        'amount',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function fundraising(): HasOne
    {
        return $this->hasOne(Fundraising::class, 'id', 'fundraising_id');
    }

    public function donater(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getHumanType(): string
    {
        return $this->fundraising->getName();
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

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @param Carbon|null $value
     * @return $this
     */
    public function setCreatedAt($value): self
    {
        $this->created_at = $value;

        return $this;
    }

    /**
     * @param Carbon|null $value
     * @return $this
     */
    public function setUpdatedAt($value): self
    {
        $this->updated_at = $value;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFundraisingId(): int
    {
        return $this->fundraising_id;
    }

    public function getFundraising(): Fundraising
    {
        return $this->fundraising;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at->setTimezone(config('app.timezone'));
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
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

    public function withDonater(): Donate
    {
        return self::with('donater')->where('id', '=', $this->getId())->first();
    }
}
