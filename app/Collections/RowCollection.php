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
        return (bool)$this->filter(static function(Row $row) use ($getUniqHash) {
            return str_contains($row->getComment(), $getUniqHash);
        })->count();
    }

    /**
     * @param string $getUniqHash
     * @return float
     */
    public function getAmountByUniqHash(string $getUniqHash): float
    {
        return floatval($this->filter(static function(Row $row) use ($getUniqHash) {
            return str_contains($row->getComment(), $getUniqHash);
        })?->first()?->getAmount() ?? 0.00);
    }
}
