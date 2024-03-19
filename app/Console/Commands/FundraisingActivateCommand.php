<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;

class FundraisingActivateCommand extends FundraisingDeactivateCommand
{
    public const string MESSAGE = 'Ви закинули актуальну виписку на видалений збір :fundraising. Збір буде відновлено автоматично.';
    public const string MESSAGE_ADMIN = ':fundraising буде відновлено автоматично.';
    public const string DAYS = '-10 days';
    protected $signature = 'fundraising:activate';

    protected $description = 'Command description';

    protected function doCommandGoal(bool $action, Fundraising $fundraising): void
    {
        $fundraising->restore();
    }

    /**
     * @return Fundraising[]|array
     */
    protected function getAllFundraisings(): array
    {
        return Fundraising::query()->where('is_enabled', '=', true)->onlyTrashed()->get()->all();
    }

    /**
     * @param int $rowsChecked
     * @return bool
     */
    protected function isNeedActionByCountRow(int $rowsChecked): bool
    {
        return (bool)$rowsChecked;
    }
}
