<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class WebhookCommand extends Command
{
    protected $signature = 'webhook {disable? : Disable webhook}';
    protected $description = 'Command that manages the state of the Telegram webhook, either setting it up or disabling it based on the provided argument.';

    public function handle(): void
    {
        $response = $this->argument('disable') ? Telegram::bot('donater-bot')->deleteWebhook() : Telegram::bot('donater-bot')->setWebhook(['url' => config('telegram.bots.donater-bot.webhook_url')]);
        $this->output->info(json_encode($response));
    }
}
