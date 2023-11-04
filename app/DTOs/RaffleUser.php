<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Collections\DonateCollection;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;

class RaffleUser implements Arrayable
{
    public const string TYPE_PER_TICKET = 'per_ticket';
    public const string TYPE_PER_PERSON = 'per_person';
    public const string TYPE_PER_PERSON_AND_SUM = 'per_person_and_sum';
    public const array TYPES = [
        self::TYPE_PER_TICKET => 'За сумою донатів (один квиток - фіксована сума)',
        self::TYPE_PER_PERSON => 'Серед учасників, які донатили (сума не важлива)',
        self::TYPE_PER_PERSON_AND_SUM => 'Серед учасників, які донатили (від суми)',
    ];
    public function __construct(
        protected User $user,
        protected DonateCollection $donateCollection,
        protected string $chance = '',
        protected bool $isWinner = false
    ) {
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

    public function getChance(): string
    {
        return $this->chance;
    }

    public function setChance(string $chance): RaffleUser
    {
        $this->chance = $chance;

        return $this;
    }

    public function isWinner(): bool
    {
        return $this->isWinner;
    }

    public function setWinner(bool $isWinner): RaffleUser
    {
        $this->isWinner = $isWinner;

        return $this;
    }
}
