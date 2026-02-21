<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {

        if (request()->is('api/*')) {
            config([
                'tenancy.bootstrappers' => [],
                'tenancy.database.auto_create_tenant_databases' => false,
                'tenancy.database.auto_delete_tenant_databases' => false,
            ]);
        }
        
        Vite::prefetch(concurrency: 3);
    }
}
