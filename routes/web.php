<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationActivityController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityController;
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
    | Profile Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/profile',
        [ProfileController::class, 'index']
    )->name('security.profile');

    Route::post(
        '/security/profile',
        [ProfileController::class, 'update']
    )->name('security.profile.update');

    /*
    |--------------------------------------------------------------------------
    | Change Password
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/password',
        [PasswordController::class, 'index']
    )->name('security.password');

    Route::post(
        '/security/password',
        [PasswordController::class, 'update']
    )->name('security.password.update');

    /*
    |--------------------------------------------------------------------------
    | Security Overview
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security',
        [SecurityController::class, 'index']
    )->name('security.overview');

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/two-factor',
        [TwoFactorController::class, 'index']
    )->name('security.two-factor');

    /*
    |--------------------------------------------------------------------------
    | Authentication Activity
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/login-history',
        [AuthenticationActivityController::class, 'index']
    )->name('security.login-history');

    Route::get(
        '/security/login-history/export',
        [AuthenticationActivityController::class, 'export']
    )->name('security.login-history.export');

    /*
    |--------------------------------------------------------------------------
    | Active Sessions
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

    /*
    |--------------------------------------------------------------------------
    | Delete Account
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/delete-account',
        [AccountController::class, 'index']
    )->name('security.delete-account');

    Route::delete(
        '/security/delete-account',
        [AccountController::class, 'destroy']
    )->name('security.delete-account.destroy');
});