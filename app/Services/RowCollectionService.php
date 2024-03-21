<?php

namespace App\Services;

use App\Collections\RowCollection;
use App\Models\Fundraising;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RowCollectionService
{
    /**
     * @param Collection|Fundraising[] $fundraisings
     * @return RowCollection
     */
    public function getRowCollection(?Collection $fundraisings = null): RowCollection
    {
        $key = sha1(serialize($fundraisings));
        $data = Cache::get($key);
        if ($data) {
            return unserialize($data);
        }
        $rows = new RowCollection();
        if (!$fundraisings || $fundraisings->isEmpty()) {
            Cache::set($key, serialize($rows), 60);
            return $rows;
        }

        $service = app(GoogleServiceSheets::class);
        foreach ($fundraisings as $fundraising) {
            $rows->push(...$service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId())->all());
        }
        Cache::set($key, serialize($rows), 60);

        return $rows;
    }
}
