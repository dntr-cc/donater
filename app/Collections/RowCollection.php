<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\Row;
use Illuminate\Support\Collection;

/**
 * @method array|Row[] all()
 * @property array|Row[] $items
 */
class RowCollection extends Collection
{
    public function hasUniqHash(string $getUniqHash): bool
    {
        return (bool)$this->filter(static function(Row $row) use ($getUniqHash) {
            return str_contains($row->getComment(), $getUniqHash);
        })->count();
    }
}
