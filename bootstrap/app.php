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
        // Global middleware (typical defaults already loaded by framework)
        // $middleware->append(...);

        // Aliases — usable in routes like ->middleware('auth') / ('admin')
        $middleware->alias([
            'auth' => App\Http\Middleware\Authenticate::class,
            'guest' => App\Http\Middleware\RedirectIfAuthenticated::class,
            'admin' => App\Http\Middleware\AdminOnly::class,
            'verifiedCsrf' => Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);

        // Groups (web already includes session & CSRF). Example if you want to customize:
        // $middleware->appendToGroup('web', ...);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Customize exception handling if needed.
    })
    ->create();