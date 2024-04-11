<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * @param $request
     * @return bool
     */
    protected function inExceptArray($request): bool
    {
        $this->except[] = '/' . config('app.dev_hash');
        $this->except[] = '/deploy';
        $this->except[] = '/fundraising/*/preload';

        return parent::inExceptArray($request);
    }
}
