<?php

declare(strict_types=1);

namespace App\Bot;

use App\Models\User;

class NotifyAllCommand extends NotifyCommand
{
    protected string $name = 'notifyall';

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
        return '/notifyall';
    }
}
