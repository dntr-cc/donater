<?php

declare(strict_types=1);

namespace App\Bot;

use App\Models\User;
use Telegram\Bot\Commands\Command;

class NotifyCommand extends Command
{
    protected string $name = 'notify';

    /**
     * @var string Command Description
     */
    protected string $description = 'Привітання';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $chatId = $this->getUpdate()?->getChat()?->getId();
        if (503910905 === (int)$chatId) {
//            foreach (User::all() as $user) {
                \Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $this->getUpdate()?->getMessage()->getText(),
                ]);
//            }
            $this->replyWithMessage(['text' => 'Вітаю!']);
        }
    }
}
