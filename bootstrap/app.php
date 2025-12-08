<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'tenant.admin' => \App\Http\Middleware\EnsureTenantAdmin::class,
        ]);

        // Redirect unauthenticated users based on context
        $middleware->redirectGuestsTo(function (Request $request) {
            if (tenancy()->initialized) {
                return route('tenant.login');
            }

            return route('admin.login');
        });

        // Redirect authenticated users away from guest routes (login, register)
        $middleware->redirectUsersTo(function (Request $request) {
            if (tenancy()->initialized) {
                return route('tenant.dashboard');
            }

            return route('admin.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
