<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\LoginService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login', ['loginHash' => app(LoginService::class)->getNewLoginHash()]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return RedirectResponse|Response|JsonResponse
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if ('http://localhost' === config('app.url')) {
            $this->fakeLogin();
            return response([], Response::HTTP_ACCEPTED);
        }

        $this->validateLogin($request);
        $service  = app(LoginService::class);
        $data = $service->getCachedLoginData($request->get('loginHash'));
        if (empty($data)) {
            return response('Login information not found', Response::HTTP_UNAUTHORIZED);
        }
        $this->guard()->login($service->getOrCreateUser($data));

        return response([], Response::HTTP_ACCEPTED);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'loginHash' => 'required|string',
        ]);
    }

    public function fakeLogin(): void
    {
        $this->guard()->login(app(LoginService::class)->getOrCreateUser([
            'id'          => 1,
            'username'    => 'test',
            'telegram_id' => 212123213213123,
            'first_name'  => 'first_name',
            'last_name'   => 'last_name',
            'is_premium'  => true,
        ], false));
    }
}
