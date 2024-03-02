<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Models\SubscribesMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscribesMigrationCommand extends Command
{
    protected $signature = 'subscribes:migration';

    protected $description = 'Command description';

    public function handle(): void
    {
        foreach (Subscribe::query()->withoutTrashed()->get()->all() as $subscribe) {
            $scheduledAt = $subscribe?->getScheduledAt();
            $scheduledAtParsed = explode(':', $scheduledAt);
            $carbon = (new Carbon('today'))->setHour((int)$scheduledAtParsed[0])->setMinutes((int)$scheduledAtParsed[1]);
            $firstMessageAt = $carbon->getTimestamp() < time() ? $carbon->modify('+1 day')->format('Y-m-d H:i') : $carbon->format('Y-m-d H:i');
            $subscribe->update([
                'first_message_at' => $firstMessageAt,
                'frequency' => \App\Models\SubscribesMessage::DAILY_NAME,
            ]);
            SubscribesMessage::create([
                'subscribes_id' => $subscribe->getId(),
                'frequency' => \App\Models\SubscribesMessage::DAILY_NAME,
                'scheduled_at' => $firstMessageAt,
            ]);
        }
    }
}
