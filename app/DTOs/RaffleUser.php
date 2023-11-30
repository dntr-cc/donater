<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Collections\DonateCollection;
use App\Models\User;

class RaffleUser
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

}
