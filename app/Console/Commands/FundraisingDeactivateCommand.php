<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;

class FundraisingDeactivateCommand extends Command
{
    public const string MESSAGE = 'Ви не закидали виписку з донатами старше 7 днів. Протягом 72 годин ваш збір :fundraising буде видалено автоматично. Якщо ваш збір зупинено - натисніть кнопку "ЗУПИНИТИ"';
    public const string MESSAGE_ADMIN = ':fundraising буде видалено автоматично за 48 годин';
    public const string DAYS = '-7 days';
    protected $signature = 'fundraising:deactivate';

    protected $description = 'Command description';

    public function handle(): void
    {
        $service = app(GoogleServiceSheets::class);
        $limit = strtotime(static::DAYS);
        foreach ($this->getAllFundraisings() as $fundraising) {
            $needAction = $this->actionStatusStart();
            $rows = $service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
            if (!$rows->count() && $this->getCreatedCondition($fundraising, $limit)) {
                $needAction = $this->actionStatusEnd();
            } else {
                foreach ($rows->all() as $item) {
                    $date = $item->getDate();
                    if (
                        preg_match('/^[0-9]{2}.[0-9]{2}.[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $date) &&
                        strtotime($date) < $limit
                    ) {
                        $needAction = $this->actionStatusEnd();
                    }
                    break;
                }
            }
            if ($needAction) {
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
        $sendMessage = ' Повідомлення доставлно волонтеру.';
        try {
            $this->notifyVolunteer($fundraising);
        } catch (\Throwable $t) {
            $sendMessage = ' Повідомлення недоставлно волонтеру.';
        }
        User::find(1)->sendBotMessage(
            strtr(static::MESSAGE . $sendMessage, [':fundraising' => route('fundraising.show', compact('fundraising'))])
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
            strtr(static::MESSAGE, [':fundraising' => route('fundraising.show', compact('fundraising'))])
        );
        $volunteer->sendBotMessage('Повідомлення для підтримки монобанку для замовлення виписки:');
        $volunteer->sendBotMessage($fundraising->getMonoRequest($fundraising->getJarLink(false)));
    }

    protected function doCommandGoal(Fundraising $fundraising): void
    {
        // do nothing
    }

    /**
     * @return Fundraising[]|array
     */
    protected function getAllFundraisings(): array
    {
        return Fundraising::query()->where('is_enabled', '=', true)->get()->all();
    }

    /**
     * @return false
     */
    protected function actionStatusStart(): bool
    {
        return false;
    }

    /**
     * @return false
     */
    protected function actionStatusEnd(): bool
    {
        return true;
    }

    /**
     * @param mixed $fundraising
     * @param false|int $limit
     * @return bool
     */
    protected function getCreatedCondition(Fundraising $fundraising, false|int $limit): bool
    {
        return $fundraising->getCreatedAt()->setTimezone(config('app.timezone'))->getTimestamp() < $limit;
    }
}
