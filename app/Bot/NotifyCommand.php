<?php

declare(strict_types=1);

namespace App\Bot;

use App\Models\User;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Exceptions\TelegramOtherException;

class NotifyCommand extends Command
{
    protected string $name = 'notify';

    /**
     * @var string Command Description
     */
    protected string $description = 'Розсилка';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $chatId = $this->getUpdate()?->getChat()?->getId();
        $blocked = [];
        $it = 0;
        if (5609509050 === (int)$chatId) {
            foreach (User::all() as $user) {
                try {
                    \Telegram::sendMessage([
                        'chat_id' => $user->getTelegramId(),
                        'text' => strtr($this->getUpdate()?->getMessage()?->getText() ?? '', ['/notify ' => '']),
                    ]);
                    $it++;
                } catch (TelegramOtherException $exception) {
                    $blocked[] = $user->getUserLink();
                }
            }
            $this->replyWithMessage(['text' => 'Blocked:' . implode(', ', $blocked) . PHP_EOL . 'Send messages: ' . $it]);
        }
    }
}
