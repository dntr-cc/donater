<?php

namespace App\Http\Resources;

use App\Models\Donate;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Donate */
class DonateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_id'        => $this->user_id,
            'fundraising_id' => $this->fundraising_id,
            'hash'      => $this->hash,
        ];
    }
}
