<?php

declare(strict_types=1);

namespace App\Bot;

use App\Models\User;
use App\Models\UserSetting;
use Telegram\Bot\Commands\Command;

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
        $blocked = $skipped = [];
        $it = 0;
        if (in_array($chatId, config('app.admins_ids'))) {
            foreach (User::all() as $user) {
                if ($user->settings->hasSetting(UserSetting::DONT_SEND_MARKETING_MESSAGES)) {
                    $skipped[] = $user->getUserLink();
                    continue;
                }
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
            $this->replyWithMessage(['text' =>
                'Blocked ('. count($blocked) . '):' . implode(', ', $blocked) . PHP_EOL . PHP_EOL .
                'Skipped ('. count($skipped) . '):' . implode(', ', $skipped) . PHP_EOL . PHP_EOL .
                'Send messages: ' . $it
            ]);
        }
    }
}
