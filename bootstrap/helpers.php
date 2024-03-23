<?php

use App\Models\User;
use App\Models\UserSetting;

function sensitive(string $text, ?User $user): string
{
    if (!$user) {
        return $text;
    }

    if (!$user->settings->hasSetting(UserSetting::USE_FEMININE_FORMS_WHEN_MY_ROLE_IS_CALLED)) {
        return $text;
    }

    return strtr($text, [
        'серійний'    => 'серійна',
        'волонтера'   => 'волонтерку',
        'донатер'     => 'донатерка',
        'донатером'   => 'донатеркою',
        'волонтер'    => 'волонтерка',
        'користувач'  => 'користувачка',
        'благодійник' => 'благодійниця',
        'військовий'  => 'військова',
        'боєць'       => 'бійчиня',
    ]);
}
