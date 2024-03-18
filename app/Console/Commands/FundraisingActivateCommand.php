<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;

class FundraisingActivateCommand extends FundraisingDeactivateCommand
{
    public const string MESSAGE = 'Ви закинули актуальну виписку на видалений збір :fundraising. Збір буде відновлено автоматично';
    public const string MESSAGE_ADMIN = ':fundraising буде відновлено автоматично';
    protected $signature = 'fundraising:activate';

    protected $description = 'Command description';

    protected function doCommandGoal(Fundraising $fundraising): void
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
     * @return false
     */
    protected function actionStatusStart(): bool
    {
        return true;
    }

    /**
     * @return false
     */
    protected function actionStatusEnd(): bool
    {
        return false;
    }
}
