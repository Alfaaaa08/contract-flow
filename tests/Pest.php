<?php

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\DatabaseMigrations::class,
)->beforeEach(function () {
    config([
        'database.connections.tenant' => config('database.connections.sqlite'),
    ]);

    $bootstrappers = config('tenancy.bootstrappers');
    $bootstrappers = array_filter(
        $bootstrappers,
        fn($b) => $b !== \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class
    );
    config(['tenancy.bootstrappers' => array_values($bootstrappers)]);
})->in('Feature', 'Unit');

function createTenant(string $id = 'test-tenant'): \App\Models\Tenant {
    $tenant = \App\Models\Tenant::factory()->create(['id' => $id]);
    $tenant->domains()->create(['domain' => "{$id}.localhost"]);
    tenancy()->initialize($tenant);
    return $tenant;
}

uses()
    ->afterEach(function () {
        $files = glob(database_path('tenant*'));
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    })->in('Feature');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});
