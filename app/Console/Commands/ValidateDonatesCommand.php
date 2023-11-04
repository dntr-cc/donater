<?php

namespace App\Console\Commands;

use App\Models\Donate;
use App\Services\GoogleServiceSheets;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram;
use Throwable;

class ValidateDonatesCommand extends Command
{
    protected $signature = 'validate:donates';

    protected $description = 'Command description';
    private GoogleServiceSheets $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = app(GoogleServiceSheets::class);
    }

    public function getService(): GoogleServiceSheets
    {
        return $this->service;
    }

    public function handle(): void
    {
        try {
            /** @var Donate $donate */
            foreach (Donate::query()->whereNull('validated_at')->get()->all() as $donate) {
                $volunteer = $donate->getVolunteer();
                $rows      = $this->service->getRowCollection($volunteer);
                if ($rows->hasUniqHash($donate->getUniqHash())) {
                    $donate->setValidatedAt(Carbon::now())->save();
                    $this->output->info('Validated object: ' . $donate->toJson());
                    $telegramId = $donate->donater()->first()?->getTelegramId();
                    if ($telegramId) {
                        Telegram::sendMessage([
                            'chat_id' => $telegramId,
                            'text'    => strtr('Ваш внесок :code було завалідовано! Подивитися звіт: :url', [
                                ':code' => $donate->getUniqHash(),
                                ':url'  => route('zvit.volunteer', ['volunteer' => $volunteer->getKey()]),
                            ]),
                        ]);
                    }
                }
            }
        } catch (Throwable $t) {
            Log::error($t->getMessage(), ['trace' => $t->getTraceAsString()]);
        }
    }
}
