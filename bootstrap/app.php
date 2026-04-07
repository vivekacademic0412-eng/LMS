<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        if (env('APP_ENV') === 'production' || str_starts_with((string) env('APP_URL', ''), 'https://')) {
            $middleware->trustProxies(at: '*');
        }

        $middleware->alias([
            'active' => \App\Http\Middleware\ActiveUserMiddleware::class,
            'activity.log' => \App\Http\Middleware\LogActivity::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'secure.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
        ]);

        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
