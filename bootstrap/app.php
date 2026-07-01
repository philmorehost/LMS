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
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware aliases (used in routes)
        $middleware->alias([
            'role'             => \App\Http\Middleware\RoleMiddleware::class,
            'bruteforce'       => \App\Http\Middleware\BruteForceProtection::class,
            'sanitize'         => \App\Http\Middleware\SanitizeInput::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
