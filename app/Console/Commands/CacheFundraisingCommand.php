<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class CacheFundraisingCommand extends Command
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
        if ($id = $this->option('id')) {
            try {
                $fundraising = Fundraising::find($id);
                $this->service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId(), \App\Services\GoogleServiceSheets::RANGE_DEFAULT, false);
            } catch (Throwable $t) {
                Log::error($t->getMessage(), ['trace' => $t->getTraceAsString()]);
            }
        }
    }
}
