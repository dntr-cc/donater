<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $hash
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class UserCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'user_id',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('order', static function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setCode(string $code): self
    {
        $this->hash = $code;

        return $this;
    }

    public function setUserId(int $userId): self
    {
        $this->user_id = $userId;

        return $this;
    }
}
