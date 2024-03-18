<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\User;

class FundraisingRemoveCommand extends FundraisingDeactivateCommand
{
    public const string MESSAGE = 'Ви не закидали виписку з донатами старше 10 днів. Ваш збір :fundraising видалено автоматично. Для відновлення вам треба закинути виписку з донатами за крайні 10 днів, тоді збір буде відновлено автоматично.';
    public const string MESSAGE_ADMIN = ':fundraising видалено автоматично.';
    public const string DAYS = '-10 days';

    protected $signature = 'fundraising:remove';

    protected $description = 'Command description';

    protected function notifyVolunteer(Fundraising $fundraising): void
    {
        $fundraising->getVolunteer()->sendBotMessage(
            strtr(static::MESSAGE, [':fundraising' => route('fundraising.show', compact('fundraising'))])
        );
    }

    protected function doCommandGoal(Fundraising $fundraising): void
    {
        $fundraising->delete();
    }
}
