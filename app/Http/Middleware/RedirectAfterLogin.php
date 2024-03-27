<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use App\Services\LoginService;
use Closure;
use Illuminate\Http\Request;

class RedirectAfterLogin
{
    protected $except = [
        '/login',
    ];

    /**
     * @param Request $request
     * @param Closure $next
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user) {
            $redirectUrl = url()->current();
            if ($redirectUrl === route('login')) {
                $redirectUrl = route('my');
            }
            session()->put(LoginController::RETURN_AFTER_LOGIN, $redirectUrl);
            session()->put(LoginController::LOGIN_HASH, app(LoginService::class)->getNewLoginHash());
        }

        return $next($request);
    }
}
