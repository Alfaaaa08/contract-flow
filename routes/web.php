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
Route::get('/', [WelcomeController::class, 'index']);