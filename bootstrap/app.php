<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // This configures where guests should be redirected.
        $middleware->redirectGuestsTo(function (Request $request) {
            // If the request is for an API route...
            if ($request->is('api/*')) {
                // ...do not redirect at all. This will allow the exception
                // handler to return a proper JSON error response.
                return null;
            }
        });
        $middleware->alias([
            'company.valid' => \App\Http\Middleware\CheckCompanyIsValid::class,
        ]);
    })
    ->withProviders([
        // This registers our new provider so Laravel will load it
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        // This tells Laravel how to handle authentication errors for API requests.
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        });
    })->create();
