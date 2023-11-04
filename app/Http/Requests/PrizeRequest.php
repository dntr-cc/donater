<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrizeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'           => ['required'],
            'description'    => ['required'],
            'avatar'         => ['required'],
            'user_id'        => ['required', 'integer'],
            'raffle_type'    => ['required'],
            'raffle_winners' => ['required', 'integer'],
            'raffle_price'   => ['required', 'numeric'],
            'available_type' => ['required'],
            'available_for'  => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
