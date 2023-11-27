<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Donate;

class DonateService
{
    public function getNewUniqueHash(): string
    {
        while (1) {
            $uniqId = uniqid('', true);
            if (0 === Donate::where('uniq_hash', '=', $uniqId)->count()) {
                return $uniqId;
            }
        }
    }
}
