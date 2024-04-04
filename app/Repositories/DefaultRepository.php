<?php

namespace App\Repositories;

use App\Services\CacheWrapper;

class DefaultRepository
{
    protected bool $rewrite = false;
    protected int $needRewrite = 0;
    protected CacheWrapper $wrapper;

    public function __construct(CacheWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function needRewrite(): self
    {
        $this->needRewrite = 1;

        return $this;
    }

    protected function lazy(callable $callback)
    {
        return $this->wrapper->lazyLoading(
            'lazy:' . sha1(get_class($this) . __METHOD__),
            $callback,
            (bool)($this->needRewrite > 0 ? --$this->needRewrite : 0)
        );
    }

}
