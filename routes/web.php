<?php

use App\Http\Controllers\AuthenticationActivityController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication Security Manager
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/two-factor',
        [TwoFactorController::class, 'index']
    )->name('security.two-factor');

    /*
    |--------------------------------------------------------------------------
    | Authentication Activity & Login History
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/login-history',
        [AuthenticationActivityController::class, 'index']
    )->name('security.login-history');

    /*
    |--------------------------------------------------------------------------
    | Active Sessions & Device Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/sessions',
        [SessionController::class, 'index']
    )->name('security.sessions');

    Route::post(
        '/security/sessions/{sessionId}/revoke',
        [SessionController::class, 'revoke']
    )->name('security.sessions.revoke');

    Route::post(
        '/security/sessions/revoke-others',
        [SessionController::class, 'revokeOthers']
    )->name('security.sessions.revoke-others');

});