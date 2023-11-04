<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class UserUsernameRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public
    function __invoke($attribute, $value, $fail)
    {
        if (User::query()->where('username', '=', $value)->count() > 0) {
            $fail('Username is forbidden');
        }
    }
}
