<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
    $gitHubUser =  Socialite::driver('github')->user();

    $user = User::updateOrCreate([
        'github_id' => $gitHubUser->id,
    ],[
        'name' => $gitHubUser->name,
        'email' => $gitHubUser->email,
        'github_token' => $gitHubUser->token,
        'github_refresh_token' => $gitHubUser->refreshToken,
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

require __DIR__.'/auth.php';
