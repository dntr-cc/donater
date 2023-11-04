<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'username'   => $this->username,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
        ];
    }
}
