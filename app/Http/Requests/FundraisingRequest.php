<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundraisingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key'            => ['required', 'regex:/^[a-zA-Z0-9_-]+$/i'],
            'name'           => ['required', 'max:60'],
            'link'           => ['required'],
            'page'           => ['required'],
            'avatar'         => ['required'],
            'description'    => [],
            'spreadsheet_id' => ['required'],
            'user_id'        => ['required', 'integer'],
            'card_mono'      => [],
            'card_privat'    => [],
            'paypal'         => [],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
