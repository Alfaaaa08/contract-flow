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
    });
}
