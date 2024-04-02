<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\Metrics;

class SubscribeReminderCommand extends DefaultCommand
{
    public const string MESSAGE = "Нам дуже прикро, що ви не користуєтесь підписками на волонтерів. Ми розуміємо, що це проблема, якщо вашого волонтера нема на сайті. Ви можете запросити людину своїм кодом донатера, після переходу по ньому відкривається спеціальне віконечко, де коротко описано ще це за сайт. Ваш код донатера:\n";
    protected $signature = 'subscribe:reminder';

    protected $description = 'Command that sends a reminder to users who have not been using volunteer subscriptions, encouraging them to utilize the feature while providing them with their unique donator code.';

    public function handle(): void
    {
        $users = User::query()->whereNotIn('id', Subscribe::query()->get()->pluck('user_id')->toArray())->get();
        $blocked = $skipped = [];
        $it = 0;
        foreach ($users->all() as $user) {
            if ($user->settings->hasSetting(UserSetting::DONT_SEND_MARKETING_MESSAGES)) {
                $skipped[] = $user->getUserLink();
                continue;
            }
            try {
                $user->sendBotMessage(self::MESSAGE . $user->getUserCode());
                $it++;
            } catch (\Throwable $exception) {
                $blocked[] = $user->getUserLink();
            }
        }
        User::find(1)->sendBotMessage(
            'subscribe:reminder skipped users' . PHP_EOL . PHP_EOL .
            'Blocked ('. count($blocked) . '):' . implode(', ', $blocked) . PHP_EOL . PHP_EOL .
            'Skipped ('. count($skipped) . '):' . implode(', ', $skipped) . PHP_EOL . PHP_EOL .
            'Send messages: ' . $it
        );
        $this->saveMetric(Metrics::SUBSCRIBE_REMINDER);
    }
}
