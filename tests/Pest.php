<?php

use App\Models\Tenant;
use App\Models\User;

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

/**
 * Create a user with JWT token
 */
function createUserWithToken(Tenant $tenant): array
{
    tenancy()->initialize($tenant);
    
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'tenant_id' => $tenant->id,
    ]);

    $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

    return [
        'user' => $user,
        'token' => $token,
        'headers' => [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ],
    ];
}