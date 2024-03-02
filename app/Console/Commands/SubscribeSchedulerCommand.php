<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
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
        $time = (new Carbon())->setTimezone(config('app.timezone'));
        foreach (Subscribe::query()->withoutTrashed()->get()->all() as $subscribe) {
            $nextMessage = $subscribe->getNextSubscribesMessage();
            if ($nextMessage->getScheduledAt() < $time) {
                Log::info('subscribe:process ' . $subscribe->getId());
                Artisan::call('subscribe:process ' . $subscribe->getId());
                SubscribesMessage::create([
                    'subscribes_id' => $subscribe->getId(),
                    'frequency' => $nextMessage->getFrequency(),
                    'scheduled_at' => $nextMessage->getScheduledAt()->modify($subscribe->getModifier($nextMessage->getFrequency())),
                ]);
            }
        }
    }
}