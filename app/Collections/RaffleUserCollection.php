<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\RaffleUser;
use App\Models\Donate;
use Illuminate\Support\Collection;

/**
 * @property array|RaffleUser[] $items
 */
class RaffleUserCollection extends Collection
{
    public function all()
    {
        usort(
            $this->items,
            fn(RaffleUser $a, RaffleUser $b) =>
                $b->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount()) <=>
                $a->getDonateCollection()->sum(fn(Donate $donate) => $donate->getAmount())
        );

        return parent::all();
    }
}
