<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\RaffleUser;
use App\Models\Donate;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property array|Donate[] $items
 * @method array|Donate[] all()
 */
class DonateCollection extends Collection
{
    public function getRaffleUserCollection(): RaffleUserCollection
    {
        $map = [];
        foreach ($this->all() as $donate) {
            $map[$donate->getUserId()][] = $donate;
        }
        $result = new RaffleUserCollection();
        foreach ($map as $donates) {
            $result->push(
                new RaffleUser(
                    $donates[0]->donater()->first(),
                    new self($donates)
                )
            );
        }

        return $result;
    }
}
