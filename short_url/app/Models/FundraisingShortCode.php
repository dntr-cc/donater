<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $fundraising_id
 * @property string $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class FundraisingShortCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'fundraising_id',
        'code',
    ];

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
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }
}
