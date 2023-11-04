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
            'user_id'      => $this->user_id,
            'volunteer_id' => $this->volunteer_id,
            'uniq_hash'    => $this->uniq_hash,
        ];
    }
}
