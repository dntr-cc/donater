<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'      => ['required', 'integer'],
            'fundraising_id' => ['required', 'integer'],
            'uniq_hash'    => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
