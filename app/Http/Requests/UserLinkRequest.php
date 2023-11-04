<?php

namespace App\Http\Requests;

use App\Rules\UserLinkIconRule;
use App\Rules\UserLinkUserRule;
use Illuminate\Foundation\Http\FormRequest;

class UserLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'link'    => ['required', 'url:http,https', 'max:170'],
            'name'    => ['required', 'max:20', 'min:2'],
            'icon'    => ['required', 'max:30', new UserLinkIconRule()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
