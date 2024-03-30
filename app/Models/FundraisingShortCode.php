<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $fundraising_id
 * @property string $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Fundraising $fundraising
 */
class FundraisingShortCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'fundraising_id',
        'code',
    ];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    /**
     * @return HasOne
     */
    public function fundraising(): HasOne
    {
        return $this->hasOne(Fundraising::class, 'id', 'fundraising_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFundraisingId(): int
    {
        return $this->fundraising_id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at->setTimezone(config('app.timezone'));
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function getFundraising(): Fundraising
    {
        return self::with('fundraising')->where('id', '=', $this->getId())->first()->fundraising;
    }

    public static function hasCode(string $code): bool
    {
        return static::query()->where('code', '=', $code)->exists();
    }
}
