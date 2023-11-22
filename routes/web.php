<?php

use App\Bot\CommandWrapper;
use App\Models\Volunteer;
use App\Services\DonateService;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/' . config('app.dev_hash'), static function () {
    $update = Telegram::commandsHandler(true);
    if (!$update->hasCommand()) {
        (new CommandWrapper())->handle($update);
    }

    return 'ok';
});

Route::get('/deploy', static function () {
    $deployNotAvailable = file_exists(base_path() . '/deploy.pid');
    if (!$deployNotAvailable) {
        Artisan::call('down', [
            '--render' => 'layouts.pause',
            '--refresh' => 10,
            '--retry' => 1,
        ]);
    }

    return response(null, $deployNotAvailable ? Response::HTTP_CONFLICT : Response::HTTP_NO_CONTENT);
})->middleware(['dev'])->name('dev_deploy');

Route::get('/', fn() => view('welcome'))->name('welcome');
Route::get('/about', fn() => redirect(\route('welcome'), Response::HTTP_FOUND)->header('Cache-Control', 'no-store, no-cache, must-revalidate'))->name('about');
Route::get('/my', [App\Http\Controllers\HomeController::class, 'index'])->name('my');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/u/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('user');
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::get('/donate', [App\Http\Controllers\DonateController::class, 'create'])->name('donate');
Route::post('/donate', [App\Http\Controllers\DonateController::class, 'store']);
Route::get('/zvit', fn() => view('zvit', ['volunteers' => Volunteer::where('is_enabled', '=', true)->get()]))->name('zvit');
Route::get('/zvit/{volunteer}', [App\Http\Controllers\VolunteerController::class, 'show'])->name('zvit.volunteer');
Route::post('/user/link', [App\Http\Controllers\UserLinkController::class, 'store'])->name('user.link');
Route::delete('/user/link/{userLink}', [App\Http\Controllers\UserLinkController::class, 'destroy'])->name('user.link.delete');
Route::patch('/user/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('user.edit');
Route::post('/user/{user}/avatar', [App\Http\Controllers\UserController::class, 'updateAvatar'])->name('user.edit.avatar');
Route::get('/roadmap', fn() => view('roadmap'))->name('roadmap');
Route::get('/raffles', fn() => view('raffles', ['volunteers' => Volunteer::whereIn('key', ['glits04_1', 'setnemo_twitter_subscribe'])->get()]))->name('raffles');
