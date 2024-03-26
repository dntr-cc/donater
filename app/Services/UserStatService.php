<?php

namespace App\Services;

use App\DTOs\DonaterInfo;
use App\DTOs\VolunteerInfo;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserStatService
{
    public function getVolunteerInfo(User $user, bool $fromCache = true) : VolunteerInfo
    {
        if ($fromCache && Cache::has($this->getCacheKey('volunteer', $user->getId()))) {
            return unserialize(Cache::get($this->getCacheKey('volunteer', $user->getId())));
        }

        $rows = app(\App\Services\RowCollectionService::class)->getRowCollection($user->getFundraisings());
        $totalSubscribers = $user->getSubscribers()->count();
        $fundraisingsCount = $user->getFundraisings()->count();
        $totalAmount = $rows->allSum();
        $sumOfDonations = $rows->allSumFromOurDonates();
        $volunteerStats = new VolunteerInfo($totalSubscribers, $fundraisingsCount, $rows->countFromOurDonates(), $rows->countDonates(), $totalAmount, $sumOfDonations);
        Cache::set($this->getCacheKey('volunteer', $user->getId()), serialize($volunteerStats), 60);

        return $volunteerStats;
    }
    public function getDonaterInfo(User $user, bool $fromCache = true) : DonaterInfo
    {
        if ($fromCache && Cache::has($this->getCacheKey('donater', $user->getId()))) {
            return unserialize(Cache::get($this->getCacheKey('donater', $user->getId())));
        }

        $rows = app(\App\Services\RowCollectionService::class)->getRowCollection($user->getFundraisings());
        $totalSubscribes = $user->getSubscribersAsSubscriber()->count();
        $donateCount = $user->getDonateCount();
        $totalAmount = $user->getDonatesSumAll();
        $prizesCount = $user->getPrizesCount();
        $donaterInfo = new DonaterInfo($totalSubscribes, $donateCount, $prizesCount, $totalAmount);
        Cache::set($this->getCacheKey('donater', $user->getId()), serialize($donaterInfo), 60);

        return $donaterInfo;
    }

    /**
     * @param string $type
     * @param int $id
     * @return string
     */
    protected function getCacheKey(string $type, int $id): string
    {
        return strtr('volunteer-info-:type-:id', [':id' => $id, ':type' => $type]);
    }
}
