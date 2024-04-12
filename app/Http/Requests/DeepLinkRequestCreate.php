<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeepLinkRequestCreate extends FormRequest
{
    public function rules(): array
    {
        return [
            'hash'       => ['required', 'unique:deep_links,hash'],
            'amount'     => ['required', 'integer', 'min:1'],
            'started_at' => ['required', 'date_format:H:i'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
