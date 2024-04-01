<?php

namespace App\Console\Commands;

use App\Events\OpenGraphRegenerateEvent;
use App\Models\Fundraising;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class FundraisingCacheCommand extends Command
{
    protected $signature = 'fundraising:cache {id}';

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
        if ($id = $this->argument('id')) {
            try {
                $fundraising = Fundraising::find($id);
                if (!$fundraising) {
                    return;
                }
                $hash = sha1(
                    $this->service->getRowCollection(
                        $fundraising->getSpreadsheetId(),
                        $fundraising->getId(),
                        GoogleServiceSheets::RANGE_DEFAULT,
                        false
                    )
                );
                $shaKey = strtr('sha1-:key', [':key' => $fundraising->getKey()]);
                $existedHash = Cache::get($shaKey);
                OpenGraphRegenerateEvent::dispatch($fundraising->getVolunteer()->getId(), OpenGraphRegenerateEvent::TYPE_USER);
                if ($existedHash !== $hash) {
                    $fundraising->getVolunteer()->sendBotMessage(
                        strtr('На вашому зборі :link оновилася виписка. Наступне повідомлення ви отримаєте коли сайт побачить зміни в виписці', [':link' => $fundraising->getShortLink()])
                    );
                }
                Cache::put($shaKey, $hash);
            } catch (Throwable $t) {
                Log::error($t->getMessage(), ['trace' => $t->getTraceAsString()]);
            }
        }
    }
}
