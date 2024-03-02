<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class SubscribeFrequencyRule implements InvokableRule
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
        if (strtotime($value) < time()) {
            $fail('Обрано дату та час в минулому');
        }
    }
}
