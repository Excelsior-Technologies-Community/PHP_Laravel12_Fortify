<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationActivityController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ThemeController;
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
    | Profile Management & Avatar
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

    Route::post(
        '/security/avatar',
        [AvatarController::class, 'update']
    )->name('security.avatar.update');

    Route::post(
        '/security/theme',
        [ThemeController::class, 'update']
    )->name('security.theme.update');

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
    | Roles & Permissions (RBAC)
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/roles',
        [RolePermissionController::class, 'index']
    )->name('security.roles');

    Route::post(
        '/security/roles',
        [RolePermissionController::class, 'storeRole']
    )->name('security.roles.store');

    Route::post(
        '/security/roles/user/{user}',
        [RolePermissionController::class, 'updateUserRole']
    )->name('security.roles.user.update');

    /*
    |--------------------------------------------------------------------------
    | Teams & Multi-Tenancy Workspaces
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/security/teams',
        [TeamController::class, 'index']
    )->name('security.teams');

    Route::post(
        '/security/teams',
        [TeamController::class, 'store']
    )->name('security.teams.store');

    Route::post(
        '/security/teams/{team}/switch',
        [TeamController::class, 'switchTeam']
    )->name('security.teams.switch');

    Route::post(
        '/security/teams/{team}/invite',
        [TeamController::class, 'inviteMember']
    )->name('security.teams.invite');

    Route::post(
        '/security/teams/{team}/remove/{user}',
        [TeamController::class, 'removeMember']
    )->name('security.teams.remove');

    /*
    |--------------------------------------------------------------------------
    | User Impersonation ("Login As User")
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/security/impersonate/stop',
        [ImpersonationController::class, 'stop']
    )->name('security.impersonate.stop');

    Route::post(
        '/security/impersonate/{user}',
        [ImpersonationController::class, 'impersonate']
    )->name('security.impersonate');

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