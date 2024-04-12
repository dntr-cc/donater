<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoginService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public const string RETURN_AFTER_LOGIN = 'RETURN_AFTER_LOGIN';
    public const string LOGIN_HASH = 'login_hash';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return View
     */
    public function showLoginForm(): View
    {
        $key = 'login:' . request()->getClientIp();
        if (RateLimiter::tooManyAttempts($key, 12)) {
            $seconds = RateLimiter::availableIn($key);
            User::find(1)->sendBotMessage('login:429' . PHP_EOL  . PHP_EOL . '`'. json_encode(
                    [
                        'request' => request()->toArray(),
                        'headers' => request()->header(),
                        'client_ips' => request()->getClientIps(),
                    ]
                ). '`'
            );

            return view('errors.429', compact('seconds'));
        }
        RateLimiter::increment($key);

        return view('auth.login', ['loginHash' => app(LoginService::class)->getNewLoginHash()]);
    }

    public function login(Request $request)
    {
        if ('http://localhost' === config('app.url') && \request()->cookies->has('fake')) {
            $this->fakeLogin();
            return new JsonResponse(['url' => session()->get(self::RETURN_AFTER_LOGIN, route('my'))], Response::HTTP_OK);
        }

        $this->validateLogin($request);
        $service = app(LoginService::class);
        $data = $service->getCachedLoginData($request->get('loginHash'));
        if (empty($data)) {
            return response('Login information not found', Response::HTTP_UNAUTHORIZED);
        }
        $this->guard()->login($service->getOrCreateUser($data));

        return new JsonResponse(['url' => session()->get(self::RETURN_AFTER_LOGIN, route('my'))], Response::HTTP_OK);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'loginHash' => 'required|string',
        ]);
    }

    public function fakeLogin(): void
    {
        $this->guard()->login(app(LoginService::class)->getOrCreateUser([
            'id'          => 5609509050,
            'username'    => 'admin',
            'first_name'  => 'Серійний донатер',
            'last_name'   => '',
            'is_premium'  => true,
        ], false));
    }
}
