<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheWrapper
{
    public function lazyLoading(string $key, callable $callback, bool $rewrite = false)
    {
        if (!$rewrite && Cache::has($key)) {
            $data = Cache::get($key);
        } else {
            $data = $callback();
            Cache::forever($key, $data);
        }

        return $data;
    }

    public function update(string $key, callable $callback): bool
    {
        return Cache::forever($key, $callback());
    }
}
