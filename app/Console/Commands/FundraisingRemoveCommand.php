<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\User;

class FundraisingRemoveCommand extends FundraisingDeactivateCommand
{
    public const string MESSAGE = 'Ви не закидали виписку більше 2 тижнів. Ваш збір :fundraising видалено автоматично';
    public const string MESSAGE_ADMIN = ':fundraising видалено автоматично';
    public const string LIMIT = '-15 days';

    protected $signature = 'fundraising:remove';

    protected $description = 'Command description';

    protected function notifyVolunteerAndAdmin(Fundraising $fundraising): void
    {
        $sendMessage = ' Повідомлення доставлно волонтеру';
        try {
            $this->notifyVolunteer($fundraising);
        } catch (\Throwable $t) {
            $sendMessage = ' Повідомлення недоставлно волонтеру';
        }
        User::find(1)->sendBotMessage(
            strtr(self::MESSAGE . $sendMessage, ['' => route('fundraising.show', compact('fundraising'))])
        );
    }

    protected function notifyVolunteer(Fundraising $fundraising): void
    {
        $fundraising->getVolunteer()->sendBotMessage(
            strtr(self::MESSAGE, ['' => route('fundraising.show', compact('fundraising'))])
        );
    }

    protected function doCommandGoal(Fundraising $fundraising): void
    {
        $fundraising->delete();
    }
}
