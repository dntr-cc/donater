<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;

class FundraisingDeactivateCommand extends Command
{
    public const string MESSAGE = 'Ви не закидали виписку більше 2 тижнів. Протягом доби ваш збір :fundraising буде видалено автоматично';
    public const string MESSAGE_ADMIN = ':fundraising буде видалено автоматично за дві доби';
    protected $signature = 'fundraising:deactivate';

    protected $description = 'Command description';

    public function handle(): void
    {
        $service = app(GoogleServiceSheets::class);
        $limit = strtotime('-14 days');
        foreach (Fundraising::query()->where('is_enabled', '=', true)->get()->all() as $fundraising) {
            $needDelete = false;
            $rows = $service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
            if (!$rows->count() || $fundraising->getCreatedAt()->setTimezone(config('app.timezone'))->getTimestamp() < $limit) {
                $needDelete = true;
            }
            if (!$needDelete) {
                foreach ($rows->all() as $item) {
                    $date = $item->getDate();
                    if (
                        preg_match('/^[0-9]{2}.[0-9]{2}.[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $date) &&
                        strtotime($date) > $limit
                    ) {
                        $needDelete = true;
                        break;
                    }
                }
            }
            if ($needDelete) {
                $this->notifyVolunteerAndAdmin($fundraising);
                $this->doCommandGoal($fundraising);
            }
        }
    }

    /**
     * @param mixed $fundraising
     * @return void
     */
    protected function notifyVolunteerAndAdmin(Fundraising $fundraising): void
    {
        $sendMessage = ' Повідомлення доставлно волонтеру';
        try {
            $this->notifyVolunteer($fundraising);
        } catch (\Throwable $t) {
            $sendMessage = ' Повідомлення недоставлно волонтеру';
        }
        User::find(1)->sendBotMessage(
            strtr(self::MESSAGE . $sendMessage, [':fundraising' => route('fundraising.show', compact('fundraising'))])
        );
    }

    /**
     * @param mixed $fundraising
     * @return void
     */
    protected function notifyVolunteer(Fundraising $fundraising): void
    {
        $volunteer = $fundraising->getVolunteer();
        $volunteer->sendBotMessage(
            strtr(self::MESSAGE, [':fundraising' => route('fundraising.show', compact('fundraising'))])
        );
        $volunteer->sendBotMessage('Повідомлення для підтримки монобанку для замовлення виписки:');
        $volunteer->sendBotMessage($fundraising->getMonoRequest($fundraising->getJarLink(false)));
    }

    protected function doCommandGoal(Fundraising $fundraising): void
    {
        // do nothing
    }
}
