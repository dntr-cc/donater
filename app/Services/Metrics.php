<?php

namespace App\Services;

use LogicException;
use Prometheus\CollectorRegistry;
use Prometheus\Counter;
use Prometheus\Exception\MetricsRegistrationException;
use PrometheusPushGateway\PushGateway;

class Metrics
{
    const string CONSOLE_JOBS = 'console';
    const string DEFAULT_METRIC_NAMESPACE = 'donater';
    const array ALLOWED_METRICS = [
        self::SUBSCRIBE_SCHEDULER => 'Run scheduler for subscription notification each 15 seconds',
        self::SUBSCRIBE_REMINDER => 'Run scheduler every Sunday at 14:00',
        self::FUNDRAISING_DEACTIVATE => 'Run scheduler every day at 09:00',
        self::FUNDRAISING_FORGET_LINKS => 'Run scheduler every 5 minutes',
        self::FUNDRAISING_ACTIVATE => 'Run scheduler every 5 minutes',
        self::FUNDRAISING_REMOVE => 'Run scheduler every day at 23:59',
        self::DONATES_VALIDATE => 'Run scheduler every 5 minutes for each fundraising',
        self::FUNDRAISING_CACHE => 'Run scheduler every 5 minutes for each fundraising',
        self::SUBSCRIBE_NOTIFY => 'Run when need to send donate notification',
    ];
    const string SUBSCRIBE_SCHEDULER = 'subscribe_scheduler';
    const string SUBSCRIBE_NOTIFY = 'subscribe_notify';
    const string SUBSCRIBE_REMINDER = 'subscribe_reminder';
    const string FUNDRAISING_DEACTIVATE = 'fundraising_deactivate';
    const string FUNDRAISING_FORGET_LINKS = 'fundraising_forget_links';
    const string FUNDRAISING_ACTIVATE = 'fundraising_activate';
    const string FUNDRAISING_REMOVE = 'fundraising_remove';
    const string DONATES_VALIDATE = 'donates_validate';
    const string FUNDRAISING_CACHE = 'fundraising_cache';
    protected PushGateway $pushGateway;
    protected CollectorRegistry $registry;


    public function __construct(PushGateway $pushGateway, CollectorRegistry $registry)
    {
        $this->pushGateway = $pushGateway;
        $this->registry = $registry;

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
        $this->validateMetricName($metricName);

        return $this->getCollectorRegistry()->getOrRegisterCounter(self::DEFAULT_METRIC_NAMESPACE, $metricName, $this->getMetricMeasurement($metricName));
    }

    public function isAllowedMetric(string $name): bool
    {
        return in_array($name, array_keys(static::ALLOWED_METRICS), true);
    }

    public function getMetricMeasurement(string $name): string
    {
        return static::ALLOWED_METRICS[$name] ?? 'unregistered metric';
    }

    /**
     * @return CollectorRegistry
     */
    protected function getCollectorRegistry(): CollectorRegistry
    {
        return $this->registry;
    }

    /**
     * @param string $metricName
     * @return void
     */
    protected function validateMetricName(string $metricName): void
    {
        if (!$this->isAllowedMetric($metricName)) {
            throw new LogicException($metricName . ' is not allowed');
        }
    }
}
