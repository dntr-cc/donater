<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\RaffleUser;
use App\Models\Donate;
use App\Models\UserSetting;
use Illuminate\Support\Collection;

/**
 * @property array|RaffleUser[] $items
 */
class RaffleUserCollection extends Collection
{
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

    public function raffle(string $type, int $price, int $winners, bool $needWinners = false): self
    {
        $usePercent = auth()?->user()?->settings->hasSetting(UserSetting::USE_PERCENT_INSTEAD_FRACTION);
        $result = [];
        foreach ($this->all() as $item) {
            $sum = $item->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount());
            if (in_array($type, [RaffleUser::TYPE_PER_TICKET, RaffleUser::TYPE_PER_PERSON_AND_SUM]) && $sum >= $price) {
                $result[] = $item;
            } elseif (RaffleUser::TYPE_PER_PERSON === $type) {
                $result[] = $item;
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
            $n = round($x/$price);
        }
        foreach ($result as $item) {
            $chance = '';
            if (in_array($type, [RaffleUser::TYPE_PER_PERSON, RaffleUser::TYPE_PER_PERSON_AND_SUM])) {
                $chance = $usePercent ? round($winners/$n * 100, 2) . '%' : "$winners/$n";
            } elseif (RaffleUser::TYPE_PER_TICKET === $type) {
                $countedWinners = round($item->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount()) / $price, 2);
                $chance = $usePercent ? (string)round($countedWinners/$n * 100, 2) . '%': "$countedWinners/$n";
            }
            $item->setChance($chance);
        }
        if ($needWinners) {
            // magic choose
        }

        return new self($result);
    }

    public function toHtml(string $type, int $price, int $winners = 0): string
    {
        return view('fundraising.predict', [
            'raffleUserCollection' => $this,
            'type'                 => $type,
            'price'                => $price,
            'winners'              => $winners,
        ])->toHtml();
    }
}
