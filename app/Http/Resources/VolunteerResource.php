<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Volunteer */
class VolunteerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'name'       => $this->name,
            'link'       => $this->link,
            'avatar'     => $this->avatar,
            'is_enabled' => $this->is_enabled,
            'user_id'    => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
