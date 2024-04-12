<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/d/{code}', static function (string $code) {
    Cache::set('deep' . $code, $code, 60);
    return redirect("https://t.me/DonaterComUaBot?start=deep{$code}")->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});
Route::get('/f/{code}', static function (string $code) {
    $str = '//donater.com.ua';
    $item = \App\Models\FundraisingShortCode::query()->where('code', '=', $code)
        ->orWhere('code', '=', mb_strtolower($code))->first();
    if ($item) {
        $fundraising = \App\Models\Fundraising::find($item->getFundraisingId());
        if ($fundraising) {
            $str .= '/fundraising/' . $fundraising->getKey();
        }
    }
    return redirect($str)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});
Route::get('/{code}', static function (string $code) {
    $str = '//donater.com.ua';
    $item = \App\Models\UserCode::query()->where('hash', '=', $code)
        ->orWhere('hash', '=', mb_strtolower($code))->first();
    if ($item) {
        $user = \App\Models\User::find($item->getUserId());
        if ($user) {
            $str .= '/u/' . $user->getUsername();
            Cache::set('fg:' . sha1(request()->userAgent() . implode(request()->ips())), $user->getId());
        }
    }
    return redirect($str)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});
Route::get('/', static function () {
    return redirect('//donater.com.ua')->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});
