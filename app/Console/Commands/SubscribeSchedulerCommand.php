<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Models\Fundraising;
use App\Models\SubscribesMessage;
use App\Services\Metrics;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
                Log::info('subscribe:notify ' . $subscribe->getId());
                $openFundraisings = $subscribe->getVolunteer()->getFundraisings()
                    ->filter(fn(Fundraising $fundraising) => $fundraising->isEnabled())
                    ->count();
                $nextMessage->update(['need_send' => (bool)$openFundraisings]);
                $code = $nextMessage->getNotificationCode();
                Artisan::call('subscribe:notify ' . $subscribe->getId() . ' ' . $code);
                $nextScheduledAt = $nextMessage->getScheduledAt()->modify($subscribe->getModifier($nextMessage->getFrequency()));
                SubscribesMessage::create([
                    'subscribes_id' => $subscribe->getId(),
                    'frequency' => $nextMessage->getFrequency(),
                    'scheduled_at' => $nextScheduledAt,
                    'need_send' => (bool)$openFundraisings,
                    'hash' => SubscribesMessage::generateHash($subscribe->getId(), $nextScheduledAt->format('Y-m-d H:i:s')),
                ]);
            }
        }
        $this->saveMetric(Metrics::SUBSCRIBE_SCHEDULER);
    }
}
