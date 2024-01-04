<?php

declare(strict_types=1);

namespace App\Services;

use App\Collections\RowCollection;
use App\DTOs\Row;
use App\Models\Donate;
use Google\Service\Sheets\ValueRange;
use Google_Service_Sheets;
use Illuminate\Support\Facades\Cache;

class GoogleServiceSheets
{
    public const string RANGE_DEFAULT = 'A:G';
    private Google_Service_Sheets $sheets;

    public function __construct(Google_Service_Sheets $sheets)
    {
        $this->sheets = $sheets;
    }

    /**
     * @param string $spreadsheetId
     * @param int $fundraisingId
     * @param string $range
     * @param bool $fromCache
     * @return RowCollection
     */
    public function getRowCollection(string $spreadsheetId, int $fundraisingId = 0, string $range = self::RANGE_DEFAULT, bool $fromCache = true): RowCollection
    {
        $collection   = new RowCollection();
        $values       = $this->getSpreadsheetValues($spreadsheetId, $range, $fromCache)->getValues() ?? [];
        $donatesItems = Donate::query()->where('fundraising_id', '=', $fundraisingId)->get();
        $donates      = [];
        foreach ($donatesItems as $donate) {
            $donates[$donate->getUniqHash()] = $donate;
        }

        array_walk($values, static function (?array $range = null) use ($donates, $collection) {
            if ($range) {
                $collection->push(new Row($range, $donates));
            }
        });

        return $collection;
    }

    public function getSpreadsheetValues(string $spreadsheetId, string $range = 'A:G', bool $fromCache = true): ValueRange
    {
        $key = 'spreadsheetId:' . $spreadsheetId . ':' . $range;
        $data = Cache::get($key);
        if ($fromCache && $data) {
            return unserialize($data);
        }

        $valueRange = $this->getSheets()->spreadsheets_values->get($spreadsheetId, $range);
        Cache::set($key, serialize($valueRange), 75);

        return $valueRange;
    }

    public function getSheets(): Google_Service_Sheets
    {
        return $this->sheets;
    }
}
