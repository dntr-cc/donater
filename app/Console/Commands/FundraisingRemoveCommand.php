<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Services\Metrics;

class FundraisingRemoveCommand extends FundraisingDeactivateCommand
{
    public const string MESSAGE = 'Ви не закидали виписку з донатами старше 10 днів. Ваш збір :fundraising видалено автоматично. Для відновлення вам треба закинути виписку з донатами за крайні 10 днів, тоді збір буде відновлено автоматично.';
    public const string MESSAGE_ADMIN = ':fundraising видалено автоматично.';
    public const string DAYS = '-10 days';
    public const string METRIC_NAME = Metrics::FUNDRAISING_REMOVE;


    protected $signature = 'fundraising:remove';

    protected $description = 'Command that automatically deletes fundraising events that haven\'t received any new donations in the past 10 days and notifies the volunteer of the event\'s deletion.';

    protected function notifyVolunteer(Fundraising $fundraising): void
    {
        $fundraising->getVolunteer()->sendBotMessage(
            strtr(static::MESSAGE, [':fundraising' => route('fundraising.show', compact('fundraising'))])
        );
    }

    protected function doCommandGoal(bool $byCountRow, bool $byCreatedDate, Fundraising $fundraising): bool
    {
        if (!$byCountRow) {
            $fundraising->delete();
            return true;
        }

        return false;
    }
}
