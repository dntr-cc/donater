<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Collections\DonateCollection;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;

class RaffleUser implements Arrayable
{
    public function __construct(protected User $user, protected DonateCollection $donateCollection)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDonateCollection(): DonateCollection
    {
        return $this->donateCollection;
    }

    #[\Override] public function toArray()
    {
        return [
            'user' => $this->getUser()->getUserHref(),
        ];
    }
}
