<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAuthEvents
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle user login events.
     */
    public function handleLogin(Login $event): void
    {
        ActivityLogger::log('login', 'User logged in successfully');
    }

    /**
     * Handle user logout events.
     */
    public function handleLogout(Logout $event): void
    {
        ActivityLogger::log('logout', 'User logged out');
    }

    /**
     * Handle failed login events.
     */
    public function handleFailedLogin(Failed $event): void
    {
        ActivityLogger::log('failed_login', 
            'Failed login attempt', 
            null, 
            [
                'email' => $event->credentials['email'] ?? 'unknown',
                'guard' => $event->guard ?? 'web'
            ]
        );
    }

    /**
     * Handle password reset events.
     */
    public function handlePasswordReset(PasswordReset $event): void
    {
        ActivityLogger::log('password_reset', 'User reset their password', $event->user);
    }

    /**
     * Handle user registration events.
     */
    public function handleRegistered(Registered $event): void
    {
        ActivityLogger::log('registered', 'New user registered', $event->user);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events): void
    {
        $events->listen(
            Login::class,
            [LogAuthEvents::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [LogAuthEvents::class, 'handleLogout']
        );

        $events->listen(
            Failed::class,
            [LogAuthEvents::class, 'handleFailedLogin']
        );

        $events->listen(
            PasswordReset::class,
            [LogAuthEvents::class, 'handlePasswordReset']
        );

        $events->listen(
            Registered::class,
            [LogAuthEvents::class, 'handleRegistered']
        );
    }
}
