<?php

namespace App\Console\Commands;

use App\Models\FundraisingShortCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FundraisingForgetLinksCommand extends Command
{
    protected $signature = 'fundraising:forget-links';

    protected $description = 'Command description';

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
    }
}
