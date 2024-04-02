<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use Illuminate\Console\Command;

class FundraisingForgetCommand extends Command
{
    protected $signature = 'fundraising:forget';

    protected $description = 'Command that flags fundraising entities as forgotten if they are older than three months, inactive, or deleted.';

    public function handle(): void
    {
        $reallyOldFundraising = strtotime('-3 month');
        foreach (Fundraising::query()->withTrashed()->where('forget', '=', false)->get()->all() as $item) {
            if ($item->getDeletedAt() || $item->getUpdatedAt()->getTimestamp() < $reallyOldFundraising) {
                if ($item->isEnabled() && !$item->getDeletedAt()) {
                    continue;
                }
                $item->update(['forget' => true]);
            }
        }
    }
}
