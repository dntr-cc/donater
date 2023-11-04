<?php

declare(strict_types=1);

namespace App\Bot;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';

    /**
     * @var string Command Description
     */
    protected string $description = 'Привітання';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->replyWithMessage(['text' => 'Вітаю! Введіть код з сайту для авторизації ⬇️']);
    }
}
