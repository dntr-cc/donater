<?php

namespace App\Repositories;

use App\Services\CacheWrapper;

class DefaultRepository
{
    protected string $model = '';
    protected bool $rewrite = false;
    protected CacheWrapper $wrapper;

    public function __construct(CacheWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    protected function lazy(callable $callback, string $uniq)
    {
        $key = strtr('lazy::model::uniq', [
            ':model'  => $this->getModelName(),
            ':uniq'   => sha1($uniq),
        ]);
        return $this->wrapper->lazyLoading(
            $key,
            $callback,
        );
    }

    public function getModelName(): string
    {
        if (empty($this->model)) {
            throw new \LogicException('model can be filled');
        }

        return $this->model;
    }

    public function getDependsKeys(): array
    {
        return [

        ];
    }
}
