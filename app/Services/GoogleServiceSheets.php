<?php

declare(strict_types=1);

namespace App\Services;

use App\Collections\RowCollection;
use App\DTOs\Row;
use App\Models\Donate;
use App\Models\Fundraising;
use Google\Service\Sheets\ValueRange;
use Google_Service_Sheets;

class GoogleServiceSheets
{
    public const RANGE_DEFAULT = 'A:G';
    private Google_Service_Sheets $sheets;

    public function __construct(Google_Service_Sheets $sheets)
    {
        $this->sheets = $sheets;
    }

    /**
     * @param Fundraising $fundraising
     * @param string $range
     * @return RowCollection
     */
    public function getRowCollection(string $spreadsheetId, int $fundraisingId = 0, string $range = self::RANGE_DEFAULT): RowCollection
    {
        $collection   = new RowCollection();
        $values       = $this->getSpreadsheetValues($spreadsheetId, $range)->getValues() ?? [];
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

    public function getSpreadsheetValues(string $spreadsheetId, string $range = 'A:G'): ValueRange
    {
        return $this->getSheets()->spreadsheets_values->get($spreadsheetId, $range);
    }

    public function getSheets(): Google_Service_Sheets
    {
        return $this->sheets;
    }
}
