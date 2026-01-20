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

    Route::get('/dashboard', fn() => Inertia::render('Tenant/Dashboard'));
    Route::get('/contracts', fn() => Inertia::render('Tenant/Contracts'));
    Route::get('/clients', fn() => Inertia::render('Tenant/Clients'));
    Route::get('/types', fn() => Inertia::render('Tenant/Types'));
    Route::get('/settings', fn() => Inertia::render('Tenant/Settings'));
});
