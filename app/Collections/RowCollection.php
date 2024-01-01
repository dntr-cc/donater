<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\Row;
use Illuminate\Support\Collection;

/**
 * @property array|Row[] $items
 * @method Row|null first(callable $callback = null, $default = null)
 */
class RowCollection extends Collection
{
    /**
     * @return array|Row[]
     */
    public function all(): array
    {
        if ($this->items[0] ?? null) {
            $amount = $this->items[0]->getAmount();
            if ('Сума' === $amount || 'sum' === $amount) {
                unset($this->items[0]);
            }
        }

        return $this->items;
    }

    public function hasUniqHash(string $getUniqHash): bool
    {
        return (bool)$this->filter(static function (Row $row) use ($getUniqHash) {
            return str_contains($row->getComment(), $getUniqHash);
        })->count();
    }

    /**
     * @param string $getUniqHash
     * @return float
     */
    public function getAmountByUniqHash(string $getUniqHash): float
    {
        return floatval(
            $this->filter(static function (Row $row) use ($getUniqHash) {
                return str_contains($row->getComment(), $getUniqHash);
            })?->first()?->getAmount() ?? 0.00
        );
    }

    public function perDay(): ?array
    {
        $perDay = $result = null;
        $sum = 0;
        foreach ($this->items as $item) {
            if ($item->getAmount() > 0) {
                $date = $item->getDate();
                $day = date('Y/m/d', strtotime($date));
                $perDay[$day] = $perDay[$day] ?? 0;
                $perDay[$day] += floatval($item->getAmount());
                $sum += floatval($item->getAmount());
            }
        }
        if ($perDay) {
            foreach ($perDay as $day => $amount) {
                $result[$day]['amount'] = $amount;
                $result[$day]['percent'] = round($amount / $sum * 100, 2);
            }
        }

        return $result;
    }
}
