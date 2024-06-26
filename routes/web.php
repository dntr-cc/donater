<?php

use App\Bot\CommandWrapper;
use App\Collections\RowCollection;
use App\Models\Donate;
use App\Models\Fundraising;
use App\Models\Prize;
use App\Services\ChartService;
use App\Services\GoogleServiceSheets;
use App\Services\RowCollectionService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
    $deployNotAvailable = file_exists(base_path() . '/deploy.pid') || file_exists(base_path() . '/deploy.php.pid');
    if (!$deployNotAvailable) {
        Artisan::call('down', [
            '--render' => 'layouts.pause',
            '--refresh' => 3,
            '--retry' => 1,
        ]);
    }

    return response(null, $deployNotAvailable ? Response::HTTP_CONFLICT : Response::HTTP_NO_CONTENT);
})->middleware(['dev'])->name('dev_deploy');

Route::get('/', static fn() => view('welcome'))->name('welcome');
Route::get('/analytics', static function () {
    $rows = Fundraising::getAllRows();
    $chartsService = app(ChartService::class);
    $charts = $chartsService->getChartPerDay($rows);
    $charts2 = $chartsService->getChartPerAmount($rows);
    $charts3 = $chartsService->getChartPerSum($rows);
    $rowCollectionService = app(RowCollectionService::class);
    $rows2 = $rowCollectionService->getRowCollectionByDonates(Donate::all());
    $chartsAll = $chartsService->getChartPerDay($rows2, 'getChartPerDay2');
    $charts2All = $chartsService->getChartPerAmount($rows2, 'getChartPerAmount2');
    $charts3All = $chartsService->getChartPerSum($rows2, 'getChartPerSum2');

    return view('analytics', compact('rows', 'charts', 'charts2', 'charts3', 'rows2', 'chartsAll', 'charts2All', 'charts3All'));
})->name('analytics');
Route::get('/about', static fn() => redirect(\route('welcome'), Response::HTTP_FOUND)->header('Cache-Control', 'no-store, no-cache, must-revalidate'))->name('about');
Route::get('/roadmap', static fn() => view('roadmap'))->name('roadmap');
Route::get('/faq', static fn() => view('faq'))->name('faq');
Route::get('/my', [App\Http\Controllers\HomeController::class, 'index'])->name('my');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/zvit', static fn() => redirect(route('welcome'), Response::HTTP_MOVED_PERMANENTLY));
Route::get('/fundraising', static fn() => redirect(route('welcome'), Response::HTTP_MOVED_PERMANENTLY));
Route::get('/zvit/{fundraising}', static fn() => redirect(route('welcome'), Response::HTTP_MOVED_PERMANENTLY));
Route::get('/fundraising/wait', static fn() => redirect(route('welcome'), Response::HTTP_MOVED_PERMANENTLY));
Route::get('/fundraising/close', static fn() => redirect(route('welcome'), Response::HTTP_MOVED_PERMANENTLY));
Route::post('/fundraising', [App\Http\Controllers\FundraisingController::class, 'store'])->name('fundraising.create');
Route::post('/fundraising/avatar', [App\Http\Controllers\FundraisingController::class, 'storeAvatar'])->name('fundraising.avatar');
Route::post('/fundraising/key', [App\Http\Controllers\FundraisingController::class, 'checkKey'])->name('fundraising.key');
Route::post('/fundraising/{fundraising}/link', [App\Http\Controllers\FundraisingController::class, 'createShortLink'])->name('fundraising.link.create');
Route::post('/fundraising/{fundraising}/preload', [App\Http\Controllers\FundraisingController::class, 'preload'])->name('fundraising.preload');
Route::get('/fundraising/{fundraising}/analytics', [App\Http\Controllers\FundraisingController::class, 'analytics'])->name('fundraising.analytics');
Route::post('/fundraising/spreadsheet', [App\Http\Controllers\FundraisingController::class, 'spreadsheet'])->name('fundraising.spreadsheet');
Route::get('/fundraising/new', [App\Http\Controllers\FundraisingController::class, 'create'])->name('fundraising.new');
Route::get('/fundraising/{fundraising}/edit', [App\Http\Controllers\FundraisingController::class, 'edit'])->name('fundraising.edit');
Route::patch('/fundraising/{fundraising}/edit', [App\Http\Controllers\FundraisingController::class, 'update'])->name('fundraising.update');
Route::get('/fundraising/{fundraising}/start', [App\Http\Controllers\FundraisingController::class, 'start'])->name('fundraising.start');
Route::get('/fundraising/{fundraising}/stop', [App\Http\Controllers\FundraisingController::class, 'stop'])->name('fundraising.stop');
Route::get('/fundraising/{fundraising}', [App\Http\Controllers\FundraisingController::class, 'show'])->name('fundraising.show');
Route::post('/fundraising/{fundraising}/prize/{prize}', [App\Http\Controllers\FundraisingController::class, 'addPrize']);
Route::delete('/fundraising/{fundraising}/prize/{prize}', [App\Http\Controllers\FundraisingController::class, 'delPrize']);

Route::get('/u/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('user');
Route::post('/user/{user}/avatar', [App\Http\Controllers\UserController::class, 'updateAvatar'])->name('user.edit.avatar');
Route::get('/donates', [App\Http\Controllers\DonateController::class, 'index'])->name('donates');
Route::delete('/donates/{donate}', [App\Http\Controllers\DonateController::class, 'destroy'])->name('donate.delete');
Route::get('/donates/rss.xml', [App\Http\Controllers\DonateController::class, 'rss'])->name('donates.rss');
Route::post('/user/link', [App\Http\Controllers\UserLinkController::class, 'store'])->name('user.link');
Route::delete('/user/link/{userLink}', [App\Http\Controllers\UserLinkController::class, 'destroy'])->name('user.link.delete');
Route::patch('/user/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('user.edit');
Route::patch('/user/{user}/settings', [App\Http\Controllers\UserSettingsController::class, 'update'])->name('user.settings');

Route::get('/prizes', static fn() => view('prize.index', ['prizes' => Prize::query()
    ->paginate(config('app.per_page.prizes'))]))->name('prizes');
Route::get('/prizes/free', static fn() => view('prize.index', ['prizes' => Prize::query()
    ->where('is_enabled', '=', true)
    ->where('available_status', '=', Prize::STATUS_NEW)
    ->whereNull('fundraising_id')
    ->paginate(config('app.per_page.prizes'))]))->name('prizes.free');
Route::get('/prizes/booked', static fn() => view('prize.index', ['prizes' => Prize::query()
    ->where('is_enabled', '=', true)
    ->whereNotNull('fundraising_id')
    ->paginate(config('app.per_page.prizes'))]))->name('prizes.booked');
Route::get('/prizes/spent', static fn() => view('prize.index', ['prizes' => Prize::query()
    ->where('is_enabled', '=', false)
    ->whereNotNull('fundraising_id')
    ->paginate(config('app.per_page.prizes'))]))->name('prizes.spent');
Route::post('/prize', [App\Http\Controllers\PrizeController::class, 'store'])->name('prize.create');
Route::post('/prize/avatar', [App\Http\Controllers\PrizeController::class, 'storeAvatar'])->name('prize.avatar');
Route::get('/prize/new', [App\Http\Controllers\PrizeController::class, 'create'])->name('prize.new');
Route::get('/prize/{prize}', [App\Http\Controllers\PrizeController::class, 'show'])->name('prize.show');
Route::get('/prize/{prize}/edit', [App\Http\Controllers\PrizeController::class, 'edit'])->name('prize.edit');
Route::get('/prize/{prize}/approve', [App\Http\Controllers\PrizeController::class, 'approve'])->name('prize.approve');
Route::get('/prize/{prize}/decline', [App\Http\Controllers\PrizeController::class, 'decline'])->name('prize.decline');
Route::patch('/prize/{prize}/edit', [App\Http\Controllers\PrizeController::class, 'update'])->name('prize.update');
Route::post('/prize/{prize}/raffle', [App\Http\Controllers\PrizeController::class, 'raffle'])->name('prize.raffle');

Route::post('/subscribe', [App\Http\Controllers\SubscribeController::class, 'store'])->name('subscribe.create');
Route::patch('/subscribe/{subscribe}', [App\Http\Controllers\SubscribeController::class, 'update'])->name('subscribe.update');
Route::delete('/subscribe/{subscribe}', [App\Http\Controllers\SubscribeController::class, 'destroy'])->name('subscribe.delete');

Route::post('/{user}/deep', [App\Http\Controllers\UserController::class, 'deepCreate'])->name('deep.create');
Route::post('/{user}/deep/{deepLink}', [App\Http\Controllers\UserController::class, 'deepRemove'])->name('deep.delete');

