<?php

namespace App\Services;

use Prometheus\CollectorRegistry;
use Prometheus\Counter;
use Prometheus\Exception\MetricsRegistrationException;
use PrometheusPushGateway\PushGateway;

class Metrics
{
    const string CONSOLE_JOBS = 'console';
    const string DEFAULT_METRIC_NAMESPACE = 'donater';
    const array ALLOWED_METRICS = [
        self::SUBSCRIBE_SCHEDULER => 'Run scheduler for subscription notification each 15 seconds'
    ];
    const string SUBSCRIBE_SCHEDULER = 'subscribe_scheduler';
    const string SUBSCRIBE_REMINDER = 'subscribe_reminder';
    const string FUNDRAISING_DEACTIVATE = 'fundraising_deactivate';
    const string FUNDRAISING_FORGET_LINKS = 'fundraising_forget_links';
    const string FUNDRAISING_ACTIVATE = 'fundraising_activate';
    const string FUNDRAISING_REMOVE = 'fundraising_remove';
    const string DONATES_VALIDATE = 'donates_validate';
    const string FUNDRAISING_CACHE = 'fundraising_cache';
    protected PushGateway $pushGateway;


    public function __construct()
    {
        $this->pushGateway = app(PushGateway::class);
    }

    public function getPushGateway(): PushGateway
    {
        return $this->pushGateway;
    }

    /**
     * @param string $metricName
     * @return Counter
     * @throws MetricsRegistrationException
     */
    public function getCounterMetric(string $metricName): Counter
    {
        if (!$this->isAllowedMetric($metricName)) {
            throw new \LogicException($metricName . ' is not allowed');
        }

        return CollectorRegistry::getDefault()->getOrRegisterCounter(self::DEFAULT_METRIC_NAMESPACE, $metricName, $this->getMetricMeasurement($metricName));
    }

    public function isAllowedMetric(string $name): bool
    {
        return in_array($name, array_keys(static::ALLOWED_METRICS), true);
    }

    public function getMetricMeasurement(string $name): string
    {
        return static::ALLOWED_METRICS[$name] ?? 'unregistered metric';
    }
}
