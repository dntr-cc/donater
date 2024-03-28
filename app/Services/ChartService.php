<?php

namespace App\Services;

use App\Collections\RowCollection;
use IcehouseVentures\LaravelChartjs\Builder;

class ChartService
{
    const array COLOR = [
        '#4dc9f6',
        '#f67019',
        '#f53794',
        '#537bc4',
        '#acc236',
        '#166a8f',
        '#00a950',
        '#58595b',
        '#8549ba',
    ];

    public function chart(): Builder
    {
        return app()->chartjs;
    }

    /**
     * @param RowCollection $rows
     * @return Builder
     */
    public function getChartPerDay(RowCollection $rows, string $name = 'linePerDay'): Builder
    {
        $perDay = $rows->perDay();

        return $this->chart()->name($name)
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels(array_keys($perDay))
            ->datasets([
                [
                    'borderColor' => "rgb(255, 99, 132)",
                    'fill'        => true,
                    'data'        => array_map(fn(array $item, string $key) => ['x' => $key, 'y' => $item['amount']], array_values($perDay), array_keys($perDay)),
                ],
            ])
            ->optionsRaw(
                "{
                    plugins: {
                      legend: false
                    },
                    scales: {
                      x: {
                        type: 'linear'
                      }
                    }
                }"
            );
    }

    /**
     * @param RowCollection $rows
     * @return Builder
     */
    public function getChartPerAmount(RowCollection $rows, string $name = 'piePerAmount'): Builder
    {
        $perSum = $rows->perAmount();

        return $this->chart()->name($name)
            ->type('pie')
            ->labels(array_keys($perSum))
            ->datasets([
                [
                    'backgroundColor'      => array_slice(self::COLOR, 0, count($perSum)),
                    'hoverBackgroundColor' => array_slice(self::COLOR, 0, count($perSum)),
                    'data'                 => array_values($perSum),
                ],
            ])
            ->optionsRaw(
                " {
                    responsive: true,
                    plugins: {
                      legend: {
                        position: 'bottom',
                      },
                      title: {
                        display: true,
                        text: 'Донати по сумі'
                      }
                    }
                  }"
            );
    }

    /**
     * @param RowCollection $rows
     * @return Builder
     */
    public function getChartPerSum(RowCollection $rows, string $name = 'piePerSum'): Builder
    {
        $perSum = $rows->perSum();

        return $this->chart()->name($name)
            ->type('pie')
            ->labels(array_keys($perSum))
            ->datasets([
                [
                    'backgroundColor'      => array_slice(self::COLOR, 0, count($perSum)),
                    'hoverBackgroundColor' => array_slice(self::COLOR, 0, count($perSum)),
                    'data'                 => array_values($perSum),
                ],
            ])
            ->optionsRaw(
                " {
                    responsive: true,
                    plugins: {
                      legend: {
                        position: 'bottom',
                      },
                      title: {
                        display: true,
                        text: 'Донати по сумі'
                      }
                    }
                  }"
            );
    }
}
