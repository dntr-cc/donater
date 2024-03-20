<?php

namespace App\Console\Commands;

use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use Illuminate\Console\Command;

class FundraisingDeactivateCommand extends Command
{
    public const string MESSAGE = 'Ви не закидали виписку з донатами старше 7 днів. Протягом 72 годин ваш збір :fundraising буде видалено автоматично. Якщо ваш збір зупинено - натисніть кнопку "ЗУПИНИТИ".';
    public const string MESSAGE_ADMIN = ':fundraising буде видалено автоматично за 48 годин.';
    public const string DAYS = '-7 days';
    protected $signature = 'fundraising:deactivate';

    protected $description = 'Command description';

    public function handle(): void
    {
        $service = app(GoogleServiceSheets::class);
        $limit = strtotime(static::DAYS);
        foreach ($this->getAllFundraisings() as $fundraising) {
            $rows = $service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
            $rowsChecked = 0;
            foreach ($rows->all() as $item) {
                $date = $item->getDate();
                if ($this->isValidDate($date, $limit)) {
                    $rowsChecked++;
                }
                break;
            }
            $byCountRow = $this->isNeedActionByCountRow($rowsChecked);
            $byCreatedDate = $this->isNeedActionByCreatedDate($fundraising->getCreatedAt()->setTimezone(config('app.timezone'))->getTimestamp(), $limit);
            $this->output->text($fundraising->getName() . 'byCountRow:' . json_encode($byCountRow) . ' byCreatedDate:' .  json_encode($byCreatedDate));
            if ($this->initDoCommandGoal($byCountRow, $byCreatedDate, $fundraising)) {
                $this->notifyVolunteerAndAdmin($fundraising);
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

    protected function initDoCommandGoal(bool $byCountRow, bool $byCreatedDate, Fundraising $fundraising): bool
    {
        $doCommandGoal = $this->doCommandGoal($byCountRow, $byCreatedDate, $fundraising);
        if ($doCommandGoal) {
            $this->output->warning($fundraising->getName() . ' doCommandGoal apply action!');
        }

        return $doCommandGoal;
    }

    protected function doCommandGoal(bool $byCountRow, bool $byCreatedDate, Fundraising $fundraising): bool
    {
        if ($byCreatedDate && !$byCountRow) {
            return true;
        }

        return false;
    }

    /**
     * @return Fundraising[]|array
     */
    protected function getAllFundraisings(): array
    {
        return Fundraising::query()->where('is_enabled', '=', true)->get()->all();
    }

    /**
     * @param int $createdTimestamp
     * @param int $limit
     * @return bool
     */
    protected function isNeedActionByCreatedDate(int $createdTimestamp, int $limit): bool
    {
        return $createdTimestamp < $limit;
    }

    /**
     * @param string $date
     * @param false|int $limit
     * @return bool
     */
    protected function isValidDate(string $date, false|int $limit): bool
    {
        return preg_match('/^[0-9]{2}.[0-9]{2}.[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $date) &&
            strtotime($date) < $limit;
    }

    /**
     * @param int $rowsChecked
     * @return bool
     */
    protected function isNeedActionByCountRow(int $rowsChecked): bool
    {
        return 0 === $rowsChecked;
    }
}
