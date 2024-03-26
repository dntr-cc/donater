<?php

namespace App\DTOs;

class DonaterInfo
{
    const string VOLUNTEER_SUBSCRIPTIONS = 'Підписок на волонтерів:';
    const string DONATION_COUNT = 'Зроблено донатів:';
    const string WEBSITE_DONATION = 'Задоначено через сайт:';
    const string PRIZE_ADDED = 'Додано призів:';
    const string PEOPLE_SUFFIX = '';
    const string COUNT_SUFFIX = '';
    const string UAH_SUFFIX = ' ₴';
    protected int $subscribes;
    protected int $donationCount;
    protected int $prizesCount;
    protected float $amountDonates;

    public function __construct(int $subscribes, int $donationCount, int $prizesCount, float $amountDonates)
    {
        $this->subscribes = $subscribes;
        $this->donationCount = $donationCount;
        $this->prizesCount = $prizesCount;
        $this->amountDonates = $amountDonates;
    }

    public function getSubscribes(): int
    {
        return $this->subscribes;
    }

    public function getDonationCount(): int
    {
        return $this->donationCount;
    }

    public function getPrizesCount(): int
    {
        return $this->prizesCount;
    }

    public function getAmountDonates(): float
    {
        return $this->amountDonates;
    }

    public function getOpenGraphArray(): array
    {
        return [
            [self::VOLUNTEER_SUBSCRIPTIONS, $this->getSubscribes() . self::PEOPLE_SUFFIX],
            [self::DONATION_COUNT, $this->getDonationCount() . self::COUNT_SUFFIX],
            [self::WEBSITE_DONATION, $this->getAmountDonates() . self::UAH_SUFFIX],
            [self::PRIZE_ADDED, $this->getPrizesCount() . self::COUNT_SUFFIX],
        ];
    }
}
