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
        if (in_array($chatId, [5609509050, 281861745])) {
            foreach (User::all() as $user) {
                try {
                    \Telegram::sendMessage([
                        'chat_id' => $user->getTelegramId(),
                        'text' => strtr($this->getUpdate()?->getMessage()?->getText() ?? '', ['/notify ' => '']),
                    ]);
                    $it++;
                } catch (\Throwable $exception) {
                    $blocked[] = $user->getUserLink();
                }
            }
            $this->replyWithMessage(['text' => 'Blocked:' . implode(', ', $blocked) . PHP_EOL . 'Send messages: ' . $it]);
        }
    }
}
