<?php

namespace App\DTOs;

class VolunteerInfo
{

    const string PEOPLE_SUFFIX = ' ос.';
    const string COUNT_SUFFIX = ' шт.';
    const string UAH_SUFFIX = ' ₴';
    const string DONORS_SUBSCRIBED = 'Підписалося донатерів:';
    const string TOTAL_COLLECTIONS = 'Всього зборів:';
    const string TOTAL_FUNDS_RAISED = 'Загалом зібрано коштів:';
    const string TOTAL_DONATIONS_RECEIVED = 'Зібрано від донатерів:';
    const string TOTAL_DONATIONS_COUNT = 'Отримано донатів з сайту:';
    const string DONATIONS_COUNT = 'Отримано донатів:';
    protected int $subscribers;
    protected int $fundraisingsCount;
    protected float $amountSum;
    protected float $amountDonates;
    protected int $donationsCount;
    protected int $donationsCountAll;

    public function __construct(int $subscribers, int $fundraisingsCount, int $donationsCount, int $donationsCountAll, float $amountSum, float $amountDonates)
    {
        $this->subscribers = $subscribers;
        $this->fundraisingsCount = $fundraisingsCount;
        $this->amountSum = $amountSum;
        $this->amountDonates = $amountDonates;
        $this->donationsCount = $donationsCount;
        $this->donationsCountAll = $donationsCountAll;
    }

    public function getSubscribers(): int
    {
        return $this->subscribers;
    }

    public function getFundraisingsCount(): int
    {
        return $this->fundraisingsCount;
    }

    public function getAmountSum(): float
    {
        return $this->amountSum;
    }

    public function getAmountDonates(): float
    {
        return $this->amountDonates;
    }

    public function getDonationsCount(): int
    {
        return $this->donationsCount;
    }

    public function getDonationsCountAll(): int
    {
        return $this->donationsCountAll;
    }

    public function getOpenGraphArray(): array
    {
        return [
            [self::DONORS_SUBSCRIBED, $this->getSubscribers() . self::PEOPLE_SUFFIX],
            [self::TOTAL_COLLECTIONS, $this->getFundraisingsCount() . self::COUNT_SUFFIX],
            [self::TOTAL_DONATIONS_COUNT, $this->getDonationsCountAll() . self::COUNT_SUFFIX],
            [self::DONATIONS_COUNT, $this->getDonationsCount() . self::COUNT_SUFFIX],
            [self::TOTAL_FUNDS_RAISED, $this->getAmountSum() . self::UAH_SUFFIX],
            [self::TOTAL_DONATIONS_RECEIVED, $this->getAmountDonates() . self::COUNT_SUFFIX],
        ];
    }
}
