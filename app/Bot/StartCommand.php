<?php

declare(strict_types=1);

namespace App\Bot;

use Illuminate\Support\Facades\Cache;
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
        // Check if the start command has been issued with a login code
        $message = $this->getUpdate()->getMessage();
        $text = $message->getText();
        if (!empty($text)) {
            $isExist = Cache::pull('login:start:' . $text);

            if ($isExist) {

                $from = $message?->getFrom();
                Cache::set('login:end:' . $text, $from->toJson(), 60);
                $this->replyWithMessage(['text' => 'Вхід дозволено!']);
                return;
            }
        }
        $this->replyWithMessage(['text' => 'Вітаю! Введіть код з сайту для авторизації ⬇️']);
    }
}
