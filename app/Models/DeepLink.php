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
 * @property int $volunteer_id
 * @property float $amount
 * @property string $hash
 * @property Carbon $started_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class DeepLink extends Model
{
    use SoftDeletes, HasFactory;

    public const string TEMPLATE_DEEP_LINK = 'dntr.cc/d/:hash';
    public const int CODE_LENGTH = 5;
    protected $fillable = [
        'volunteer_id',
        'hash',
        'amount',
        'started_at',
    ];

    public function volunteer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'volunteer_id');
    }

    protected function startedAt(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format('H:i'),
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DeepLink
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getVolunteerId(): int
    {
        return $this->volunteer_id;
    }

    /**
     * @param int $volunteer_id
     * @return DeepLink
     */
    public function setVolunteerId(int $volunteer_id): self
    {
        $this->volunteer_id = $volunteer_id;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return DeepLink
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return strtr(self::TEMPLATE_DEEP_LINK, [':hash' => $this->getHash()]);
    }

    /**
     * @param string $hash
     * @return DeepLink
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getStartedAt(): string
    {
        return $this->started_at;
    }

    /**
     * @param Carbon $started_at
     * @return DeepLink
     */
    public function setStartedAt(Carbon $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * @return Carbon|null
     */
    public function getDeletedAt(): ?Carbon
    {
        return $this->deleted_at;
    }

    /**
     * @param Carbon|null $deleted_at
     * @return DeepLink
     */
    public function setDeletedAt(?Carbon $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public static function createHash(): string
    {
        $vocabulary = array_merge(range('a', 'z'), range(0, 9));
        $vocabularyIndexes = count($vocabulary) - 1;
        $code = '';
        do {
            $length = self::CODE_LENGTH;
            while ($length--) {
                $code .= $vocabulary[mt_rand(0, $vocabularyIndexes)];
            }
        } while (static::query()->where('hash', '=', $code)->exists());

        return $code;
    }
}
