<?php

namespace App\Console\Commands;

use App\Models\FundraisingShortCode;
use App\Services\Metrics;
use Illuminate\Support\Facades\DB;

class FundraisingForgetLinksCommand extends DefaultCommand
{
    protected $signature = 'fundraising:forget-links';

    protected $description = 'Command that cleans up old fundraising link entries, leaving only the most recent link for each fundraising event.';

    public function handle(): void
    {
        $excludeIds1 = FundraisingShortCode::query()
            ->select([DB::raw('max(id) as id'), 'fundraising_id', DB::raw('count(id) as cnt')])
            ->groupBy('fundraising_id')
            ->havingRaw('count(*) > 1')
            ->get()->pluck('id');
        $excludeIds2 = FundraisingShortCode::query()
            ->select([DB::raw('max(id) as id'), 'fundraising_id', DB::raw('count(id) as cnt')])
            ->groupBy('fundraising_id')
            ->havingRaw('count(*) = 1')
            ->get()->pluck('id');
        $needRemove = FundraisingShortCode::query()->whereNotIn('id', array_merge($excludeIds1->toArray(), $excludeIds2->toArray()))->get();
        foreach ($needRemove->all() as $item) {
            $item->delete();
        }
        $this->saveMetric(Metrics::FUNDRAISING_FORGET_LINKS);
    }
}
