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
                Log::info('subscribe:process ' . $subscribe->getId());
                $openFundraisings = $subscribe->getVolunteer()->getFundraisings()
                    ->filter(fn(Fundraising $fundraising) => $fundraising->isEnabled())
                    ->count();
                $nextMessage->update(['has_open_fundraisings' => (bool)$openFundraisings]);
                Artisan::call('subscribe:process ' . $subscribe->getId());
                SubscribesMessage::create([
                    'subscribes_id' => $subscribe->getId(),
                    'frequency' => $nextMessage->getFrequency(),
                    'scheduled_at' => $nextMessage->getScheduledAt()->modify($subscribe->getModifier($nextMessage->getFrequency())),
                    'has_open_fundraisings' => (bool)$openFundraisings,
                ]);
            }
        }
    }
}
