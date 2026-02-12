<?php

declare(strict_types=1);

use App\Http\Controllers\Central\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\ContractController;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', fn() => Inertia::render('Tenant/Home'));

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::delete('/contracts/bulk-destroy', [ContractController::class, 'bulkDestroy'])->name('contracts.bulk-destroy');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
    
    Route::get('/clients', fn() => Inertia::render('Clients/Clients'));
    Route::get('/types', fn() => Inertia::render('Types/Types'));
    Route::get('/settings', fn() => Inertia::render('Settings/Settings'));
});
