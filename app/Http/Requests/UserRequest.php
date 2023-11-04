<?php

namespace App\Http\Requests;

use App\Rules\UserUsernameRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username'   => ['min:3', 'max:50', new UserUsernameRule()],
            'first_name' => ['max:50',],
            'last_name'  => ['max:50'],
            'avatar'     => ['file:image'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
