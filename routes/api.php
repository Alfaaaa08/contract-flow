<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central API Routes
|--------------------------------------------------------------------------
|
| API routes for the central application (main domain).
| These routes handle admin authentication and tenant management.
|
*/

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes (requires authentication + admin)
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Tenant management
        Route::apiResource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus']);
    });
});
