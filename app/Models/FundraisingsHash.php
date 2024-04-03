<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $hash
 */
class FundraisingsHash extends Model
{
    protected $fillable = [
        'id',
        'hash',
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FundraisingsHash
     */
    public function setId(int $id): self
    {
        $this->id = $id;

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
     * @param string $hash
     * @return FundraisingsHash
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
