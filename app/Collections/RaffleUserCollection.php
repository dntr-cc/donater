<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\RaffleUser;
use App\Models\Donate;
use App\Models\Prize;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\Winner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

/**
 * @property array|RaffleUser[] $items
 */
class RaffleUserCollection extends Collection
{
    public const string PRIZE_WINNER_MESSAGE = 'Вітаю! Ви переможець розіграшу на зборі ":fundraising". Ви виграли ":prize". Зв\'яжіться з :prizeOwner щоб отримати приз';
    public const string FUNDRAISING_OWNER_MESSAGE = 'На вашому зборі ":fundraising" відбувся розіграш призу ":prize". Переможець :winner';

    /**
     * @return array|RaffleUser[]
     */
    public function all(): array
    {
        usort(
            $this->items,
            fn(RaffleUser $a, RaffleUser $b) => $b->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount()) <=>
                $a->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount())
        );

        return parent::all();
    }

    public function raffle(Prize $prize, bool $needWinners = false): self
    {
        $type = $prize->getRaffleType();
        $price = $prize->getRafflePrice();
        $winners = $prize->getRaffleWinners();
        $usePercent = auth()?->user()?->settings->hasSetting(UserSetting::USE_PERCENT_INSTEAD_FRACTION);
        $result = [];
        foreach ($this->all() as $item) {
            $sum = $item->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount());
            if (in_array($type, [RaffleUser::TYPE_PER_TICKET, RaffleUser::TYPE_PER_PERSON_AND_SUM]) && $sum >= $price) {
                $result[$item->getUser()->getId()] = $item;
            } elseif (RaffleUser::TYPE_PER_PERSON === $type) {
                $result[$item->getUser()->getId()] = $item;
            }
        }
        $n = 0;
        if (in_array($type, [RaffleUser::TYPE_PER_PERSON, RaffleUser::TYPE_PER_PERSON_AND_SUM])) {
            $n = count($result);
        } elseif (RaffleUser::TYPE_PER_TICKET === $type) {
            $x = 0;
            foreach ($result as $item) {
                $x += $item->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount());
            }
            $n = round($x / $price);
        }
        foreach ($result as $item) {
            $chance = '';
            if (in_array($type, [RaffleUser::TYPE_PER_PERSON, RaffleUser::TYPE_PER_PERSON_AND_SUM])) {
                $chance = $usePercent ? round($winners / $n * 100, 2) . '%' : "$winners/$n";
            } elseif (RaffleUser::TYPE_PER_TICKET === $type) {
                $countedWinners = round($item->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount()) / $price, 2);
                $chance = $usePercent ? (string)round($countedWinners / $n * 100, 2) . '%' : "$countedWinners/$n";
            }
            $item->setChance($chance);
        }
        if ($needWinners) {
            $result = $this->getWinners($prize, $result, $price, $winners);
        }

        return new self($result);
    }

    public function winnersToHtml(): string
    {
        $template = Winner::WINNER_TEMPLATE;
        $result = '';
        foreach ($this->all() as $it => $winner) {
            $result .= strtr($template, [
                ':number' => $it + 1,
                ':winner' => $winner->getUser()->getUserHref(),
            ]);
        }

        return $result;
    }

    /**
     * @param array $result
     * @param float $price
     * @param array $filterIds
     * @return array
     */
    protected function getRaffleNumbers(array $result, float $price, array $filterIds = []): array
    {
        $raffleNumbers = [];
        foreach ($result as $item) {
            $userId = $item->getUser()->getId();
            if (in_array($userId, $filterIds)) {
                continue;
            }
            $raffleNumbers = array_merge(
                $raffleNumbers,
                array_fill(
                    0,
                    (int)($item->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount()) / $price),
                    $userId
                )
            );
        }

        return $raffleNumbers;
    }

    public function saveWinners(Prize $prize): self
    {
        $prize->setEnabled(false)->save();
        foreach ($this->all() as $item) {
            Winner::create([
                'user_id' => $item->getUser()->getId(),
                'prize_id' => $prize->getId(),
            ]);
        }

        return $this;
    }

    /**
     * @param Prize $prize
     * @param array $result
     * @param float $price
     * @param int $winners
     * @return array
     */
    protected function getWinners(Prize $prize, array $result, float $price, int $winners): array
    {
        $winnersIdsFilter = [];
        $raffleNumbers = $this->getRaffleNumbers($result, $price);
        $chosenWinners = 0;
        $winnersResult = [];
        while (1) {
            if (empty($raffleNumbers)) {
                throw new \LogicException('Не вийшло обрати переможця');
            }
            shuffle($raffleNumbers);
            shuffle($raffleNumbers);
            shuffle($raffleNumbers);
            $count = count($raffleNumbers) - 1;
            $winnerNumber = mt_rand(0, $count);
            $winnersUserId = $raffleNumbers[$winnerNumber];
            $winner = User::find($winnersUserId);
            try {
                $winnersIdsFilter = array_merge($winnersIdsFilter, [$winner->getId()]);
                $raffleNumbers = $this->getRaffleNumbers($result, $price, $winnersIdsFilter);
                $prizeFundraising = $prize->fundraising()->first();
                $fundraisingName = $prizeFundraising->getName();
                $prizeName = $prize->getName();
                Telegram::sendMessage([
                    'chat_id' => $winner?->getTelegramId(),
                    'text' => strtr(self::PRIZE_WINNER_MESSAGE, [
                        ':fundraising' => $fundraisingName,
                        ':prize' => $prizeName,
                        ':prizeOwner' => $prize->donater()->first()->getUserLink(),
                    ])
                ]);
                try {
                    Telegram::sendMessage([
                        'chat_id' => $prizeFundraising?->getVolunteer()->getTelegramId(),
                        'text' => strtr(self::FUNDRAISING_OWNER_MESSAGE, [
                            ':fundraising' => $fundraisingName,
                            ':prize' => $prizeName,
                            ':winner' => $winner->getUserLink(),
                        ])
                    ]);
                } catch (\Throwable $throwable) {
                }
                $chosenWinners++;
                $winnersResult[] = $result[$winnersUserId]->setWinner(true);
                if ($winners === $chosenWinners) {
                    break;
                }
            } catch (Throwable $t) {
                Log::error($t->getMessage(), [':winner' => $winner?->getFullName(), 'trace' => $t->getTraceAsString()]);
            }
        }

        return $winnersResult;
    }
}
