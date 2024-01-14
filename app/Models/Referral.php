<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $user_id
 * @property int $referral_id
 * @property User $inviter
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referral_id',
    ];

    public function inviter(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): Referral
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getReferralId(): int
    {
        return $this->referral_id;
    }

    public function setReferralId(int $referralId): Referral
    {
        $this->referral_id = $referralId;

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

    public function getInviter(): User
    {
        return self::with('inviter')->where('id', '=', $this->getId())->first()->inviter;
    }
}
