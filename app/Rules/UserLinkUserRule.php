<?php

namespace App\Rules;

use App\Models\UserLink;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class UserLinkUserRule implements InvokableRule
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
        if (UserLink::query()->where('user_id', '=', $value)->count() > 10) {
            $fail('Тільки 10 посилань дозволено');
        }
    }
}
