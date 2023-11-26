<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VolunteerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key'            => ['required', 'regex:/^[a-zA-Z-_]+$/i'],
            'name'           => ['required', 'max:50'],
            'link'           => ['required'],
            'page'           => ['required'],
            'avatar'         => ['required'],
            'description'    => [],
            'spreadsheet_id' => ['required'],
            'user_id'        => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
