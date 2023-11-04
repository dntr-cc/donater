<?php

namespace App\Rules;

use App\Models\UserLink;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class UserLinkIconRule implements InvokableRule
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
        if (!isset(UserLink::ICONS[$value])) {
            $fail('The :attribute must be choosen from select.');
        }
    }
}
