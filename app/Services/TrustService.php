<?php

namespace App\Services;

use App\Models\Donate;
use App\Models\Subscribe;
use App\Models\SubscribesMessage;
use App\Models\SubscribesTrustCode;

class TrustService
{
    public function countTrust(int $donaterId, int $volunteerId, int $days = 0): int
    {
        $dateStart = $this->getStartOfNotificationEra();
        $dateEnd = date('Y-m-d H:i:s', strtotime( $days ? strtr('-:days day', [':days' => $days]) : 'now'));
        if ($dateStart > $dateEnd) {
            throw new \LogicException('Trust not allowed for the date under start logging open fundraisings');
        }
        $promises = SubscribesMessage::query()
            ->whereIn(
                'subscribes_id',
                $this->getIdsForProcessing($donaterId, $volunteerId, $dateStart, $dateEnd)
            )
            ->where('need_send', '=', true)
            ->where('created_at', '>=', $dateStart)
            ->where('created_at', '<=', $dateEnd)
            ->pluck('id')->toArray();
        $donated = 0;
        if ($promises) {
            $donated = SubscribesTrustCode::query()->whereIn('id', $promises)->count();
        }

        return !empty($promises) ? (int)(round($donated / count($promises), 2) * 100) : 0;
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
        return '2024-03-27 09:59:59';
    }
}
