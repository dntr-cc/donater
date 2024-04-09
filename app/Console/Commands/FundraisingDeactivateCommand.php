<?php

namespace App\Console\Commands;

use App\Events\OpenGraphRegenerateEvent;
use App\Models\Fundraising;
use App\Models\User;
use App\Services\GoogleServiceSheets;
use App\Services\Metrics;

class FundraisingDeactivateCommand extends DefaultCommand
{
    public const string MESSAGE = 'Ви не закидали виписку з донатами старше 7 днів.😞 Якщо в виписці не буде записів старше 10 днів то ваш збір :fundraising буде видалено автоматично.';
    public const string MESSAGE_ADMIN = ':fundraising було повідомлено, що скоро його буде видалено автоматично';
    public const string DAYS = '-7 days';
    public const string METRIC_NAME = Metrics::FUNDRAISING_DEACTIVATE;
    protected $signature = 'fundraising:deactivate';

    protected $description = 'Command that deactivates active fundraising events that haven\'t received any donations in the past 7 days, and notifies the fundraisers about the impending deletion.';

    public function handle(): void
    {
        $service = app(GoogleServiceSheets::class);
        $limit = strtotime(static::DAYS);
        foreach ($this->getAllFundraisings() as $fundraising) {
            if (!$fundraising) {
                continue;
            }
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
            $byCreatedDate = $this->isNeedActionByCreatedDate($fundraising->getCreatedAt()->getTimestamp(), $limit);
            $this->output->text($fundraising->getName() . 'byCountRow:' . json_encode($byCountRow) . ' byCreatedDate:' .  json_encode($byCreatedDate));
            if ($this->initDoCommandGoal($byCountRow, $byCreatedDate, $fundraising)) {
                $this->notifyVolunteerAndAdmin($fundraising);
            }
        }
        $this->saveMetric(static::METRIC_NAME);
    }

    /**
     * @param mixed $fundraising
     * @return void
     */
    protected function notifyVolunteerAndAdmin(Fundraising $fundraising): void
    {
        $sendMessage = ' Повідомлення доставлно волонтеру.';
        try {
            $this->notifyVolunteer($fundraising, true);
        } catch (\Throwable $t) {
            $sendMessage = ' Повідомлення недоставлно волонтеру.';
        }
        User::find(1)->sendBotMessage(
            strtr(static::MESSAGE . $sendMessage, [':fundraising' => route('fundraising.show', compact('fundraising'))]),
            true
        );
    }

    /**
     * @param mixed $fundraising
     * @param bool $throw
     * @return void
     * @throws \Throwable
     */
    protected function notifyVolunteer(Fundraising $fundraising, bool $throw = false): void
    {
        $volunteer = $fundraising->getVolunteer();
        $volunteer->sendBotMessage(
            strtr(static::MESSAGE, [':fundraising' => route('fundraising.show', compact('fundraising'))])
        );
        $volunteer->sendBotMessage('Повідомлення для підтримки монобанку для замовлення виписки:', $throw);
        $volunteer->sendBotMessage($fundraising->getMonoRequest($fundraising->getJarLink(false)), $throw);
    }

    protected function initDoCommandGoal(bool $byCountRow, bool $byCreatedDate, Fundraising $fundraising): bool
    {
        $doCommandGoal = $this->doCommandGoal($byCountRow, $byCreatedDate, $fundraising);
        if ($doCommandGoal) {
            $this->output->warning($fundraising->getName() . ' doCommandGoal apply action!');
            OpenGraphRegenerateEvent::dispatch($fundraising->getVolunteer()->getId(), OpenGraphRegenerateEvent::TYPE_USER);

        }

        return $doCommandGoal;
    }

    protected function doCommandGoal(bool $byCountRow, bool $byCreatedDate, Fundraising $fundraising): bool
    {
        if (!$byCreatedDate) {
            return false;
        }

        if ($byCountRow) {
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
            strtotime($date) > $limit;
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
