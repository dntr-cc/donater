<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundraisingLinkCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'min:5', 'max:20', 'unique:fundraising_short_codes,code'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
