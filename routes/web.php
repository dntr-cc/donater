<?php

use App\Bot\CommandWrapper;
use App\Models\Fundraising;
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
Route::get('/roadmap', fn() => view('roadmap'))->name('roadmap');
Route::get('/my', [App\Http\Controllers\HomeController::class, 'index'])->name('my');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/zvit', fn() => redirect(route('fundraising.all'), Response::HTTP_MOVED_PERMANENTLY));
Route::get('/fundraising', fn() => view('fundraising.index', ['fundraisings' => Fundraising::query()->paginate(3)->fragment('fundraising')]))->name('fundraising.all');
Route::get('/zvit/{fundraising}', fn(Fundraising $fundraising) => redirect(route('fundraising.show', compact('fundraising')), Response::HTTP_MOVED_PERMANENTLY));
Route::get('/fundraising/{fundraising}', [App\Http\Controllers\FundraisingController::class, 'show'])->name('fundraising.show');
Route::get('/raffles', fn() => view('fundraising.raffles', data: ['fundraisings' => Fundraising::query()->where('is_enabled', '=', true)->whereNotIn('user_id', [1,3])->get()]))->name('raffles');
Route::post('/fundraising', [App\Http\Controllers\FundraisingController::class, 'store'])->name('fundraising.create');
Route::post('/fundraising/avatar', [App\Http\Controllers\FundraisingController::class, 'storeAvatar'])->name('fundraising.avatar');
Route::post('/fundraising/key', [App\Http\Controllers\FundraisingController::class, 'checkKey'])->name('fundraising.key');
Route::post('/fundraising/spreadsheet', [App\Http\Controllers\FundraisingController::class, 'spreadsheet'])->name('fundraising.spreadsheet');
Route::get('/fundraising/new', [App\Http\Controllers\FundraisingController::class, 'create'])->name('fundraising.new');
Route::get('/fundraising/{fundraising}/edit', [App\Http\Controllers\FundraisingController::class, 'edit'])->name('fundraising.edit');
Route::patch('/fundraising/{fundraising}/edit', [App\Http\Controllers\FundraisingController::class, 'update'])->name('fundraising.update');
Route::get('/fundraising/{fundraising}/start', [App\Http\Controllers\FundraisingController::class, 'start'])->name('fundraising.start');
Route::get('/fundraising/{fundraising}/stop', [App\Http\Controllers\FundraisingController::class, 'stop'])->name('fundraising.stop');
Route::get('/fundraising/{fundraising}/raffle', [App\Http\Controllers\FundraisingController::class, 'raffle'])->name('fundraising.raffle');

Route::get('/u/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('user');
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::get('/volunteers', [App\Http\Controllers\UserController::class, 'volunteers'])->name('volunteers');
Route::post('/user/{user}/avatar', [App\Http\Controllers\UserController::class, 'updateAvatar'])->name('user.edit.avatar');
Route::get('/donates', [App\Http\Controllers\DonateController::class, 'index'])->name('donates');
Route::get('/donates/rss.xml', [App\Http\Controllers\DonateController::class, 'rss'])->name('donates.rss');
Route::get('/donate', [App\Http\Controllers\DonateController::class, 'create'])->name('donate');
Route::post('/donate', [App\Http\Controllers\DonateController::class, 'store']);
Route::post('/user/link', [App\Http\Controllers\UserLinkController::class, 'store'])->name('user.link');
Route::delete('/user/link/{userLink}', [App\Http\Controllers\UserLinkController::class, 'destroy'])->name('user.link.delete');
Route::patch('/user/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('user.edit');
