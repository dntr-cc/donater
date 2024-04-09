<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Services\Metrics;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class SubscribeSchedulerCommand extends DefaultCommand
{
    protected $signature = 'subscribe:scheduler';

    protected $description = 'Command that schedules the next subscription notifications for all active subscriptions, and automatically sends the notifications if they are due.';

    public function handle(): void
    {
        $time = (new Carbon())->setTimezone(config('app.timezone'))->modify('+1 second');
        foreach (Subscribe::query()->withoutTrashed()->get()->all() as $subscribe) {
            $nextMessage = $subscribe->getNextSubscribesMessage();
            if ($nextMessage->getScheduledAt() < $time) {
                Artisan::call(strtr('subscribe:notify {id} {code}', ['{id}' => $subscribe->getId(), '{code}' => $nextMessage->getNotificationCode()]));
            }
        }
        $this->saveMetric(Metrics::SUBSCRIBE_SCHEDULER);
    }
}
