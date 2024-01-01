<?php

namespace App\Console\Commands;

use App\Models\Donate;
use App\Services\GoogleServiceSheets;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram;
use Throwable;

class FillAmountCommand extends Command
{
    protected $signature = 'fill:amount';

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
            foreach (Donate::query()->where('amount', '=', 0)->get()->all() as $donate) {
                $fundraising = $donate->getFundraising();
                $rows      = $this->service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
                if ($rows->hasUniqHash($donate->getUniqHash())) {
                    $donate->setAmount($rows->getAmountByUniqHash($donate->getUniqHash()))->save();
                    $this->output->info('Fill object: ' . $donate->toJson());
                }
            }
        } catch (Throwable $t) {
            Log::error($t->getMessage(), ['trace' => $t->getTraceAsString()]);
        }
    }
}
