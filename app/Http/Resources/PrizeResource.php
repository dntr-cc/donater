<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Prize */
class PrizeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'user_id'        => $this->user_id,
            'raffle_type'    => $this->raffle_type,
            'raffle_winners' => $this->raffle_winners,
            'raffle_price'   => $this->raffle_price,
            'avatar'         => $this->avatar,
            'available_type' => $this->available_type,
            'available_for'  => $this->available_for,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
