<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $previous = url()->full();
            $route = str_contains($previous, config('app.url')) ? $previous : route('my');
            session()->put(LoginController::RETURN_AFTER_LOGIN, $route);

            return route('login');
        }
    }
}
