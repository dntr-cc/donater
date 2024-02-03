<?php

declare(strict_types=1);

namespace App\Collections;

use App\DTOs\Row;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property array|Row[] $items
 * @method Row|null first(callable $callback = null, $default = null)
 */
class RowCollection extends Collection
{
    public const string UAH_10 = 'сума донатів до 10 грн.';
    public const string UAH_100 = 'сума донатів до 100 грн.';
    public const string UAH_500 = 'сума донатів до 500 грн.';
    public const string UAH_1000 = 'сума донатів до 1000 грн.';
    public const string UAH_1000_PLUS = 'сума донатів 1000+ грн.';

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

    public function perDay(): ?array
    {
        $perDay = $result = [];
        $sum = 0;
        $daysTimestamp = [];
        foreach ($this->all() as $item) {
            if ($item->getAmount() > 0 && !$item->isOwnerTransaction()) {
                $date = $item->getDate();
                $timestamp = strtotime($date);
                if (!$timestamp) {
                    continue;
                }
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

    public function perAmount(): ?array
    {
        $result = $this->getEmptyResult();
        foreach ($this->all() as $item) {
            if ($item->getAmount() > 0 && !$item->isOwnerTransaction()) {
                $amount = (float)$item->getAmount();
                $type = match (true) {
                    $amount <= 10 => self::UAH_10,
                    $amount <= 100 => self::UAH_100,
                    $amount <= 500 => self::UAH_500,
                    $amount <= 1000 => self::UAH_1000,
                    $amount > 1000 => self::UAH_1000_PLUS,
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

    public function perSum(): ?array
    {
        $result = $this->getEmptyResult();
        foreach ($this->all() as $item) {
            if ($item->getAmount() > 0 && !$item->isOwnerTransaction()) {
                $amount = (float)$item->getAmount();
                $type = match (true) {
                    $amount <= 10 => self::UAH_10,
                    $amount <= 100 => self::UAH_100,
                    $amount <= 500 => self::UAH_500,
                    $amount <= 1000 => self::UAH_1000,
                    $amount > 1000 => self::UAH_1000_PLUS,
                    default => null,
                };
                if ($type) {
                    $result[$type] = $result[$type] ?? 0;
                    $result[$type] += $amount;
                }
            }
        }

        return $result;
    }

    public function analyticsToText(string $additionalTitle = ''): string
    {
        $perDays = $this->perDay();
        if (count($perDays) === 0) {
            return '';
        }

        $text = 'Аналітика';
        if ($additionalTitle) {
            $text .= $additionalTitle;
        }
        $text .= PHP_EOL . PHP_EOL;
        $text .= 'Донати по дням:' . PHP_EOL;
        foreach ($perDays as $day => $data) {
            $text .= "$day: зібрали {$data['amount']}, що є {$data['percent']}% від всієї суми\n";
        }

        $perAmount = $this->perAmount();
        $text .= PHP_EOL . 'Донати по сумі:' . PHP_EOL;
        foreach ($perAmount as $type => $count) {
            $text .= Str::ucfirst($type) . ": $count шт.\n";
        }

        $perSum = $this->perSum();
        $text .= PHP_EOL . 'Сума донатів по сумі:' . PHP_EOL;
        foreach ($perSum as $type => $sum) {
            $text .= Str::ucfirst($type) . ": разом $sum грн.\n";
        }

        return $text;
    }

    /**
     * @return int[]
     */
    protected function getEmptyResult(): array
    {
        return [
            self::UAH_10        => 0,
            self::UAH_100       => 0,
            self::UAH_500       => 0,
            self::UAH_1000      => 0,
            self::UAH_1000_PLUS => 0,
        ];
    }
}
