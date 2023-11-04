<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VolunteerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key'       => ['required'],
            'name'       => ['required'],
            'link'       => ['required'],
            'page'       => ['required'],
            'avatar'     => ['required'],
            'is_enabled' => ['required'],
            'user_id'    => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
