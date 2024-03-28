<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheWrapper
{
    public static function get(string $key, callable $function, array $args = [], int $seconds = 300): mixed
    {
        if (Cache::has($key)) {
            return unserialize(Cache::get($key));
        }
        Cache::set($key, serialize($value = $function(...$args)), $seconds);

        return $value;
    }
}
