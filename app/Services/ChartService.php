<?php

namespace App\Services;

use IcehouseVentures\LaravelChartjs\Builder;

class ChartService
{
    public function chart(): Builder
    {
        return app()->chartjs;
    }
}
