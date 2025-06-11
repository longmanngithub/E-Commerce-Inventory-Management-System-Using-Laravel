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
        // Middleware configuration goes here
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Exception handling configuration goes here
    })
    ->withProviders([
        App\Providers\AuthServiceProvider::class,
    ])
    ->create();
