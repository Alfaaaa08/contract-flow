<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ContractController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ContractTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('jwt.auth')->group(function () {
        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::get('auth/me', [AuthController::class, 'me']);

        // Contracts
        Route::get('contracts/stats', [ContractController::class, 'stats']);
        Route::get('contracts/expiring', [ContractController::class, 'expiring']);
        Route::delete('contracts/bulk', [ContractController::class, 'bulkDestroy']);
        Route::apiResource('contracts', ContractController::class);

        // Clients
        Route::get('clients/{client}/contracts', [ClientController::class, 'contracts']);
        Route::apiResource('clients', ClientController::class);

        // Contract types
        Route::get('contract-types/{contractType}/contracts', [ContractTypeController::class, 'contracts']);
        Route::apiResource('contract-types', ContractTypeController::class);
    });
});
