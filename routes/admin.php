<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded within the central domain group in web.php.
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (unauthenticated)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])
            ->name('login');
        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
    });

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // Dashboard (placeholder until Phase 2.4)
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Tenant Management (placeholder until Phase 2.5)
        Route::resource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])
            ->name('tenants.toggle-status');
    });
});
