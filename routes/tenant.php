<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', fn() => Inertia::render('Tenant/Home'));

    Route::get('/dashboard', fn() => Inertia::render('Dashboard/Dashboard'));
    Route::get('/contracts', [App\Http\Controllers\ContractController::class, 'index'])->name('contracts.index');
    Route::get('/clients', fn() => Inertia::render('Clients/Clients'));
    Route::get('/types', fn() => Inertia::render('Types/Types'));
    Route::get('/settings', fn() => Inertia::render('Settings/Settings'));
});
