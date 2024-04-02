<?php

namespace App\Console\Commands;

use App\Services\Metrics;
use Illuminate\Console\Command;
use Prometheus\CollectorRegistry;

abstract class DefaultCommand extends Command
{
    public function saveMetric(string $name): void
    {
        $metrics = app(Metrics::class);
        $metrics->getCounterMetric($name)->inc();
        $metrics->getPushGateway()->push(CollectorRegistry::getDefault(), Metrics::CONSOLE_JOBS, ['instance' => Metrics::DEFAULT_METRIC_NAMESPACE]);
    }
}
