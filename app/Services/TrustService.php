<?php

namespace App\Services;

use App\Models\Donate;
use App\Models\Subscribe;
use App\Models\SubscribesMessage;

class TrustService
{
    public function countTrust(int $donaterId, int $volunteerId, int $days = 1): int
    {
        $dateStart = $this->getStartOfNotificationEra();
        $dateEnd = date('Y-m-d H:i:s', strtotime(strtr('-:days day', [':days' => $days])));
        $promises = SubscribesMessage::query()
            ->whereIn(
                'subscribes_id',
                $this->getIdsForProcessing($donaterId, $volunteerId, $dateStart, $dateEnd
                )
            )
            ->where('created_at', '>=', $dateStart)
            ->where('created_at', '<=', $dateEnd)
            ->get();
        $result = [];
        $subscribes = [];
        $subscribesDeleted = [];
        foreach ($promises->all() as $promise) {
            $id = $promise->getSubscribesId();
            if (!isset($subscribes[$id])) {
                $subscribes[$id] = Subscribe::withTrashed()->find($id);
                if (!$subscribes[$id]) {
                    continue;
                }
                if ($subscribes[$id]->getDeletedAt()) {
                    $subscribesDeleted[$id] = true;
                }
            }
            $result[$id]['amount'] = $subscribes[$id]->getAmount();
            $result[$id]['count'] = $result[$id]['count'] ?? 0;
            $result[$id]['count']++;
        }
        $promiseTotal = 0;
        foreach ($result as $id => $item) {
            if ($subscribesDeleted[$id] ?? false) {
                $result[$id]['count']--;
            }
            $promiseTotal += round(floatval($result[$id]['count'] * $result[$id]['amount']), 2);
        }
        $donatesTotal = Donate::query()->withoutGlobalScope('order')
            ->join('fundraisings', 'fundraisings.id', '=', 'donates.fundraising_id')
            ->where('donates.user_id', '=', $donaterId)
            ->where('fundraisings.user_id', '=', $volunteerId)
            ->where('donates.created_at', '>=', $dateStart)
            ->where('donates.created_at', '<=', $dateEnd)
            ->get()->sum('amount');

        return (int)(round($donatesTotal / $promiseTotal, 2) * 100);
    }

    /**
     * @param int $userId
     * @param int $volunteerId
     * @param string $dateStart
     * @param string $dateEnd
     * @return array
     */
    public function getIdsForProcessing(int $userId, int $volunteerId, string $dateStart, string $dateEnd): array
    {
        return SubscribesMessage::query()->withoutGlobalScope('order')
            ->select(['subscribes_id'])
            ->join('subscribes', 'subscribes.id', '=', 'subscribes_messages.subscribes_id')
            ->where('subscribes.user_id', '=', $userId)
            ->where('subscribes.volunteer_id', '=', $volunteerId)
            ->where('subscribes_messages.created_at', '>=', $dateStart)
            ->where('subscribes_messages.created_at', '<=', $dateEnd)
            ->groupBy('subscribes_id')
            ->havingRaw('COUNT(subscribes_id) > 1')
            ->get()->pluck('subscribes_id')->toArray();
    }

    /**
     * @return string
     */
    public function getStartOfNotificationEra(): string
    {
        return '2024-03-02 21:00:00';
    }
}
