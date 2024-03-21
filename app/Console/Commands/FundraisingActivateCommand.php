<?php

namespace App\Console\Commands;

use App\Events\OpenGraphRegenerateEvent;
use App\Models\Fundraising;

class FundraisingActivateCommand extends FundraisingDeactivateCommand
{
    public const string MESSAGE = 'Ви закинули актуальну виписку на видалений збір :fundraising. Збір буде відновлено автоматично.';
    public const string MESSAGE_ADMIN = ':fundraising буде відновлено автоматично.';
    public const string DAYS = '-10 days';
    protected $signature = 'fundraising:activate';

    protected $description = 'Command description';


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
