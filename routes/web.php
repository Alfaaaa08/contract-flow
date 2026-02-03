<?php

declare(strict_types=1);


use App\Http\Controllers\ContractController;
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

Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');

foreach (config('tenancy.central_domains') as $domain) {
    Route::group(['domain' => 'admin.contractflow.test'], function () {
        Route::get('/contracts', [ContractController::class, 'index']);
    });
}
