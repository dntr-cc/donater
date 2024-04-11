<?php

namespace App\Repositories;

use App\Services\CacheWrapper;
use LogicException;

class DefaultRepository
{
    protected string $model = '';
    protected bool $rewrite = false;
    protected CacheWrapper $wrapper;

    public function __construct(CacheWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function update(callable $callback, string $uniq): bool
    {
        $key = strtr('lazy::model::uniq', [
            ':model' => $this->getModelName(),
            ':uniq'  => sha1($uniq),
        ]);

        return $this->wrapper->update($key, $callback);
    }

    protected function lazy(callable $callback, string $uniq, bool $rewrite = false)
    {
        $key = strtr('lazy::model::uniq', [
            ':model' => $this->getModelName(),
            ':uniq'  => sha1($uniq),
        ]);
        return $this->wrapper->lazyLoading(
            $key,
            $callback,
            $rewrite
        );
    }

    public function getModelName(): string
    {
        if (empty($this->model)) {
            throw new LogicException('model can be filled');
        }

        return $this->model;
    }
}
