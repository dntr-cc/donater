<?php

namespace App\Console\Commands;

use App\Models\Fundraising;

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

    protected function doCommandGoal(bool $byCountRow, bool $byCreatedDate, Fundraising $fundraising): bool
    {
        if (!$byCountRow) {
            $fundraising->delete();
            return true;
        }

        return false;
    }

    /**
     * @param int $rowsChecked
     * @return bool
     */
    protected function isNeedActionByCountRow(int $rowsChecked): bool
    {
        return $rowsChecked > 0;
    }
}
