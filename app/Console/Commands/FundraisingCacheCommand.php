<?php

namespace App\Console\Commands;

use App\DTOs\Row;
use App\Events\OpenGraphRegenerateEvent;
use App\Models\Fundraising;
use App\Models\FundraisingsHash;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use App\Services\Metrics;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class FundraisingCacheCommand extends DefaultCommand
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
        $volunteer = $fundraising = null;
        if ($id = $this->argument('id')) {
            try {
                $fundraising = Fundraising::withTrashed()->find($id);
                if (!$fundraising) {
                    return;
                }
                $hash = sha1(json_encode($this->getCurrentHash($fundraising)));
                $hashItem = FundraisingsHash::firstOrNew(['id' => $fundraising->getId()]);
                $existedHash = $hashItem->getHash();
                $volunteer = $fundraising->getVolunteer();
                OpenGraphRegenerateEvent::dispatch($volunteer->getId(), OpenGraphRegenerateEvent::TYPE_USER);
                if ($existedHash !== $hash) {
                    $message = strtr(
                        'На вашому зборі :link оновилася виписка. Наступне повідомлення ви отримаєте коли сайт побачить зміни в виписці',
                        [':link' => $fundraising->getShortLink()]
                    );
                    $volunteer->sendBotMessage($message);
                    User::find(1)->sendBotMessage($message);
                }
                if ($hashItem->isNew()) {
                    $hashItem->setHash('')->save();
                }
                $hashItem->update(['hash' => $hash]);
            } catch (Throwable $t) {
                if (str_contains($t->getMessage(), 'bot was blocked by the user')) {
                    $fundraising?->update(['forget' => true]);
                }
                Log::error(
                    strtr('fundraising:cache :user =>', [':user' => $volunteer ? $volunteer->getUsername() : 'none-user']) . $t->getMessage(),
                    ['trace' => $t->getTraceAsString()]
                );
            }
            $this->saveMetric(Metrics::FUNDRAISING_CACHE);
        }
    }

    /**
     * @param Fundraising $fundraising
     * @return array
     */
    protected function getCurrentHash(Fundraising $fundraising): array
    {
        $tmp = [];
        foreach ($this->getAll($fundraising) as $item) {
            $tmp[] = $item->toArray();
        }

        return $tmp;
    }

    /**
     * @param Fundraising $fundraising
     * @return Row[]
     */
    protected function getAll(Fundraising $fundraising): array
    {
        return $this->service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId(), GoogleServiceSheets::RANGE_DEFAULT, false)->all();
    }
}
