<?php

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->beforeEach(function () {
    config([
        'database.connections.tenant' => config('database.connections.sqlite'),
        'tenancy.database.create' => false,
    ]);

    $bootstrappers = config('tenancy.bootstrappers');
    if (($key = array_search(\Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class, $bootstrappers)) !== false) {
        unset($bootstrappers[$key]);
        config(['tenancy.bootstrappers' => array_values($bootstrappers)]);
    }
})->in('Feature', 'Unit');

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