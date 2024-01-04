<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\Row;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Collection;

use function PHPUnit\Framework\matches;

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
        $daysTimestamp = [];
        foreach ($this->all() as $item) {
            if ($item->getAmount() > 0) {
                $date = $item->getDate();
                $timestamp = strtotime($date);
                $daysTimestamp[] = $timestamp;
                $day = date('d/m/Y', $timestamp);
                $perDay[$day] = $perDay[$day] ?? 0;
                $perDay[$day] += floatval($item->getAmount());
                $sum += floatval($item->getAmount());
            }
        }
        if ($perDay) {
            $period = new DatePeriod(
                new DateTime(date('Y-m-d', min($daysTimestamp))),
                new DateInterval('P1D'),
                new DateTime(date('Y-m-d', max($daysTimestamp))),
            );
            foreach ($period as $value) {
                $result[$value->format('d/m/Y')] = ['amount' => 0.00, 'percent' => 0];
            }
            foreach ($perDay as $day => $amount) {
                $result[$day]['amount'] = $amount;
                $result[$day]['percent'] = round($amount / $sum * 100, 2);
            }
        }

        return $result;
    }
    public function perSum(): ?array
    {
        $result = null;
        foreach ($this->all() as $item) {
            if ($item->getAmount() > 0) {
                $sum = (float)$item->getAmount();
                $type = match (true) {
                    $sum === 0.00 => null,
                    $sum <= 10 => 'донат до 10 грн.',
                    $sum <= 100 => 'донат до 100 грн.',
                    $sum <= 500 => 'донат до 500 грн.',
                    $sum <= 1000 => 'донат до 1000 грн.',
                    $sum > 1000 => 'донати 1000+ грн.',
                    default => null,
                };
                if ($type) {
                    $result[$type] = $result[$type] ?? 0;
                    ++$result[$type];
                }
            }
        }

        return $result;
    }
}
