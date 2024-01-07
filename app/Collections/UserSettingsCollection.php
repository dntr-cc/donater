<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\UserSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property array|UserSetting[] $items
 * @method array|UserSetting[] all()
 */
class UserSettingsCollection extends Collection
{
    public function hasSetting(string $setting): bool
    {
        foreach ($this->all() as $item) {
            if ($setting === $item->getSetting()) {
                return true;
            }
        }

        return false;
    }
}
