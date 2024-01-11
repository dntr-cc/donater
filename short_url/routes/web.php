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

Route::get('/{code}', static function (string $code) {
    $str = '//donater.com.ua';
    $item = \App\Models\UserCode::query()->where('hash', '=', $code)->first();
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
