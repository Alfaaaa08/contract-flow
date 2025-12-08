<?php

declare(strict_types=1);

use App\Http\Controllers\Central\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Central\DashboardController;
use App\Http\Controllers\Central\TenantController;
use App\Http\Controllers\Central\TenantImpersonationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register central admin routes for your application.
| These routes are loaded within the central domain group in web.php.
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (unauthenticated)
    Route::middleware('guest')->group(function () {
        Route::controller(AuthenticatedSessionController::class)->group(function () {
            Route::get('login', 'create')->name('login');
            Route::post('login', 'store');
        });
    });

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Tenant Management
        Route::resource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])
            ->name('tenants.toggle-status');
        Route::get('tenants/{tenant}/login-as', [TenantImpersonationController::class, 'generateLoginUrl'])
            ->name('tenants.login-as');
    });
});
