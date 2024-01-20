<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Models\User;
use Illuminate\Console\Command;

class SubscribeReminderCommand extends Command
{
    public const string MESSAGE = "Нам дуже прикро, що ви не користуєтесь підписками на волонтерів. Ми розуміємо, що це проблема, якщо вашого волонтера нема на сайті. Ви можете запросити людину своїм кодом донатера, після переходу по ньому відкривається спеціальне віконечко, де коротко описано ще це за сайт. Ваш код донатера:\n";
    protected $signature = 'subscribe:reminder';

    protected $description = 'Command description';

    public function handle(): void
    {
        $users = User::query()->whereNotIn('id', Subscribe::query()->get()->pluck('user_id')->toArray())->get();

        $block = [];
        foreach ($users->all() as $user) {
            try {
                $user->sendBotMessage(self::MESSAGE . $user->getUserCode());
            } catch (\Throwable $t) {
                $block[] = $user;
            }
        }
        $txt = '';
        foreach ($block as $b) {
            $txt .= $b->getUserLink() . PHP_EOL;
        }
        User::find(1)->sendBotMessage("subscribe:reminder skipped users\n\n" . $txt);
    }
}
