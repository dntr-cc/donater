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
    public function getRaffleUserCollection(array $filterUserIds = []): RaffleUserCollection
    {
        $map = [];
        foreach ($this->all() as $donate) {
            if (in_array($donate->getUserId(), $filterUserIds)) {
                continue;
            }
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


    public function getVolunteerIds(): array
    {
        $result = [];
        foreach ($this->all() as $donate) {
            $result[] = $donate->getFundraising()->getUserId();
        }

        return array_unique($result);
    }

    /**
     * @param int $id
     * @return DonateCollection
     */
    public function getDonatesByVolunteer(int $id): DonateCollection
    {
        $result = new DonateCollection();
        foreach ($this->all() as $donate) {
                $volunteer = $donate->getFundraising()->getVolunteer();
                if ($volunteer && $id === $volunteer->getId()) {
                    $result->push($donate);
                }
            }

        return $result;
    }
}
