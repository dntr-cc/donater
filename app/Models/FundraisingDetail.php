<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property array $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class FundraisingDetail extends Model
{
    use SoftDeletes, HasFactory;

    const string CARD_MONO = 'card_mono';
    const string PAYPAL = 'paypal';
    const string CARD_PRIVAT = 'card_privat';
    const string REPORT = 'report';

    protected $fillable = [
        'id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getCardMono(): string
    {
        return $this->getData()[static::CARD_MONO] ?? '';
    }

    /**
     * @return string
     */
    public function getCardPrivat(): string
    {
        return $this->getData()[static::CARD_PRIVAT] ?? '';
    }

    /**
     * @return string
     */
    public function getPayPal(): string
    {
        return $this->getData()[static::PAYPAL] ?? '';
    }

    /**
     * @param array $data
     * @return FundraisingDetail
     */
    public function setData(array $data): self
    {
        $tmpData = $this->data ?? [];
        foreach ($data as $key => $datum) {
            if (empty($datum)) {
                unset($tmpData[$key]);
                unset($data[$key]);
            }
        }
        $this->data = array_merge($tmpData, $data);

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
}
