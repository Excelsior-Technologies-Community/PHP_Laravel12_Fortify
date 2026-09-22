<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class AuthenticationActivitySubscriber
{
    /**
     * Handle successful login.
     */
    public function handleLogin(Login $event): void
    {
        LoginActivity::create([
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'activity' => 'login',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle failed login.
     */
    public function handleFailed(Failed $event): void
    {
        $email = $event->credentials['email'] ?? null;

        LoginActivity::create([
            'user_id' => $event->user?->id,
            'email' => $email,
            'activity' => 'failed',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Handle logout.
     */
    public function handleLogout(Logout $event): void
    {
        if (! $event->user) {
            return;
        }

        LoginActivity::create([
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'activity' => 'logout',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Register event listeners.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [self::class, 'handleLogin']
        );

        $events->listen(
            Failed::class,
            [self::class, 'handleFailed']
        );

        $events->listen(
            Logout::class,
            [self::class, 'handleLogout']
        );
    }
}