<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\Tenant\ProjectController;
use App\Http\Controllers\Api\Tenant\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant API Routes
|--------------------------------------------------------------------------
|
| API routes for tenant applications (tenant subdomains).
| These routes handle tenant user authentication, projects, and user management.
|
*/

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Protected routes (requires authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Projects (all authenticated users)
        Route::apiResource('projects', ProjectController::class);

        // User management (tenant admin only)
        Route::apiResource('users', UserController::class)->middleware('tenant.admin');
    });
});
