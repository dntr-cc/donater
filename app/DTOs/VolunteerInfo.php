<?php

namespace App\DTOs;

class VolunteerInfo
{

    const string PEOPLE_SUFFIX = ' ос.';
    const string COUNT_SUFFIX = ' шт.';
    const string UAH_SUFFIX = ' грн.';
    const string DONORS_SUBSCRIBED = 'Підписалося донатерів:';
    const string TOTAL_COLLECTIONS = 'Всього зборів:';
    const string TOTAL_FUNDS_RAISED = 'Загалом зібрано коштів:';
    const string TOTAL_DONATIONS_RECEIVED = 'Зібрано від донатерів:';
    protected int $subscribers;
    protected int $fundraisingsCount;
    protected float $amountSum;
    protected float $amountDonates;

    public function __construct(int $subscribers, int $fundraisingsCount, float $amountSum, float $amountDonates)
    {
        $this->subscribers = $subscribers;
        $this->fundraisingsCount = $fundraisingsCount;
        $this->amountSum = $amountSum;
        $this->amountDonates = $amountDonates;
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

    public function getOpenGraphArray(): array
    {
        return [
            [self::DONORS_SUBSCRIBED, $this->getSubscribers() . self::PEOPLE_SUFFIX],
            [self::TOTAL_COLLECTIONS, $this->getFundraisingsCount() . self::COUNT_SUFFIX],
            [self::TOTAL_FUNDS_RAISED, $this->getAmountSum() . self::UAH_SUFFIX],
            [self::TOTAL_DONATIONS_RECEIVED, $this->getAmountDonates() . self::COUNT_SUFFIX],
        ];
    }
}
