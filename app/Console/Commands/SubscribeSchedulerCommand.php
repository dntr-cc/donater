<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Models\Fundraising;
use App\Models\SubscribesMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SubscribeSchedulerCommand extends Command
{
    protected $signature = 'subscribe:scheduler';

    protected $description = 'Command description';

    public function handle(): void
    {
        $time = (new Carbon())->setTimezone(config('app.timezone'))->modify('+61 second');
        foreach (Subscribe::query()->withoutTrashed()->get()->all() as $subscribe) {
            $nextMessage = $subscribe->getNextSubscribesMessage();
            if ($nextMessage->getScheduledAt() < $time) {
                Log::info('subscribe:notify ' . $subscribe->getId());
                $openFundraisings = $subscribe->getVolunteer()->getFundraisings()
                    ->filter(fn(Fundraising $fundraising) => $fundraising->isEnabled())
                    ->count();
                $nextMessage->update(['has_open_fundraisings' => (bool)$openFundraisings]);
                $code = $nextMessage->getNotificationCode();
                Artisan::call('subscribe:notify ' . $subscribe->getId() . ' ' . $code);
                $nextScheduledAt = $nextMessage->getScheduledAt()->modify($subscribe->getModifier($nextMessage->getFrequency()));
                SubscribesMessage::create([
                    'subscribes_id' => $subscribe->getId(),
                    'frequency' => $nextMessage->getFrequency(),
                    'scheduled_at' => $nextScheduledAt,
                    'has_open_fundraisings' => (bool)$openFundraisings,
                    'hash' => SubscribesMessage::generateHash($subscribe->getId(), $nextScheduledAt->format('Y-m-d H:i:s')),
                ]);
            }
        }
    }
}
