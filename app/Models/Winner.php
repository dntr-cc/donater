<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $prize_id
 */
class Winner extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'prize_id',
    ];

    public const string WINNER_TEMPLATE = '<p>Переможець #:number: :winner</p>';

    public function winner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function prize(): HasOne
    {
        return $this->hasOne(Prize::class, 'id', 'prize_id');
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): Winner
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getPrizeId(): int
    {
        return $this->prize_id;
    }

    public function setPrizeId(int $prize_id): Winner
    {
        $this->prize_id = $prize_id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWinner(): User
    {
        return self::with('winner')->where('id', '=', $this->getId())->first()->winner;
    }
}
