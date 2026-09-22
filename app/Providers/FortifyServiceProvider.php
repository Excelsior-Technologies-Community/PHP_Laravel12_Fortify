<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Fortify Actions
        |--------------------------------------------------------------------------
        */

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::updateUserProfileInformationUsing(
            UpdateUserProfileInformation::class
        );

        Fortify::updateUserPasswordsUsing(
            UpdateUserPassword::class
        );

        Fortify::resetUserPasswordsUsing(
            ResetUserPassword::class
        );

        Fortify::redirectUserForTwoFactorAuthenticationUsing(
            RedirectIfTwoFactorAuthenticatable::class
        );

        /*
        |--------------------------------------------------------------------------
        | Authentication Views
        |--------------------------------------------------------------------------
        */

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', [
                'request' => $request,
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Password Confirmation View
        |--------------------------------------------------------------------------
        */

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        /*
        |--------------------------------------------------------------------------
        | Two-Factor Authentication Challenge View
        |--------------------------------------------------------------------------
        */

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        /*
        |--------------------------------------------------------------------------
        | Login Rate Limiting
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('login', function (Request $request) {

            $throttleKey = Str::transliterate(
                Str::lower(
                    $request->input(Fortify::username())
                ) .
                '|' .
                $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        /*
        |--------------------------------------------------------------------------
        | Two-Factor Rate Limiting
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('two-factor', function (Request $request) {

            return Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            );
        });
    }
}