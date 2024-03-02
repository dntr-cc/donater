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
        if (mb_strlen($value) < 3) {
            $fail('Username закороткий. Від 3 символів.');
        }
        foreach (['admin', 'administrator', 'moderator', 'support'] as $forbidden) {
            if (str_contains(mb_strtolower($value), $forbidden)) {
                $fail('Username містить в собі заборонено слово.');
            }
        }
        if (User::query()->where('username', '=', $value)->count() > 0) {
            $fail('Username зайнятий.');
        }
    }
}
