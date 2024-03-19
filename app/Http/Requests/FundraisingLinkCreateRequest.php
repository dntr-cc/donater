<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundraisingLinkCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'min:5', 'max:20'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
