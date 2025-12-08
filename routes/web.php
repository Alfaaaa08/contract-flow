<?php

declare(strict_types=1);

use App\Http\Controllers\Central\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central Domain Routes
|--------------------------------------------------------------------------
|
| Routes for the central application (main domain).
| Only admin routes are handled here. User authentication is on tenant domains.
|
*/

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', [WelcomeController::class, 'index']);

        require __DIR__.'/admin.php';

        // Central API routes (stateless, no CSRF)
        Route::prefix('api')->withoutMiddleware([
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ])->group(function () {
            require __DIR__.'/api.php';
        });
    });
}
