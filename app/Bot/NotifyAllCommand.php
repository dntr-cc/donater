<?php

declare(strict_types=1);

namespace App\Bot;

use App\Models\User;
use App\Models\UserSetting;

class NotifyAllCommand extends NotifyCommand
{
    protected string $name = 'notify-all';

    /**
     * @var string Command Description
     */
    protected string $description = 'Розсилка';


    /**
     * @param User $user
     * @return bool
     */
    protected function skipCondition(mixed $user): bool
    {
        return false;
    }

    protected function replaceCommandText(): string
    {
        return '/notify ';
    }
}
