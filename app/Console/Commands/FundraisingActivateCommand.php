<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Services\Metrics;

class FundraisingActivateCommand extends FundraisingDeactivateCommand
{
    public const string MESSAGE = 'Ви закинули актуальну виписку на видалений збір :fundraising. Збір буде відновлено автоматично.';
    public const string MESSAGE_ADMIN = ':fundraising буде відновлено автоматично.';
    public const string DAYS = '-10 days';
    public const string METRIC_NAME = Metrics::FUNDRAISING_ACTIVATE;

    protected $signature = 'fundraising:activate';

    protected $description = 'Command that restores and activates previously deactivated fundraising events if the requirements for activation are met, and informs the volunteers about this event re-activation.';


    /**
     * @param mixed $fundraising
     * @return void
     */
    protected function notifyVolunteer(Fundraising $fundraising): void
    {
        $fundraising->getVolunteer()->sendBotMessage(
            strtr(static::MESSAGE, [':fundraising' => route('fundraising.show', compact('fundraising'))])
        );
    }

    protected function doCommandGoal(bool $byCountRow, bool $byCreatedDate, Fundraising $fundraising): bool
    {
        if ($byCountRow) {
            $fundraising->restore();
            $fundraising->update(['forget' => false]);
            return true;
        }

        return false;
    }

    /**
     * @return Fundraising[]|array
     */
    protected function getAllFundraisings(): array
    {
        return Fundraising::query()->where('is_enabled', '=', true)->onlyTrashed()->get()->all();
    }
}
