<?php

use App\Models\Contract;
use App\Models\User;
use App\Models\ContractType;
use App\Models\Client;
use function Pest\Laravel\actingAs;

// LISTING

it('shows contracts page to authenticated user', function () {
    $tenant = createTenant('test-tenant');
    $user   = User::factory()->create();

    actingAs($user)
        ->get('http://test-tenant.localhost/contracts')
        ->assertStatus(200)
        ->assertInertia(
            fn($page) => $page
                ->component('Contracts/Contracts')
                ->has('contracts')
                ->has('filters')
        );
});

it('returns all contracts when no filter applied', function () {
    $tenant = createTenant('test-tenant');
    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    Contract::factory()->count(3)->create([
        'name'             => 'Name',
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id

    ]);

    $user = User::factory()->create();

    $response = actingAs($user)
        ->get('http://test-tenant.localhost/contracts');

    $contracts = $response->original->getData()['page']['props']['contracts'];

    expect($contracts)->toHaveCount(3);
});

// FILTERING

it('filters contracts by name', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    Contract::factory()->create([
        'name'             => 'Alpha Project',
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);

    Contract::factory()->create([
        'name'             => 'Beta Project',
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);

    $user = User::factory()->create();

    $response = actingAs($user)
        ->get('http://test-tenant.localhost/contracts?search=Alpha');

    $response->assertStatus(200);

    $props     = $response->original->getData()['page']['props'];
    $contracts = $props['contracts'];

    expect($contracts)->toHaveCount(1);
    expect($contracts[0]['name'])->toBe('Alpha Project');
});


it('filters contracts by status', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    Contract::factory()->create([
        'name'             => 'Draft Contract',
        'status'           => 1,
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);

    Contract::factory()->create([
        'name'             => 'Active Contract',
        'status'           => 2,
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);

    $user = User::factory()->create();

    $response = actingAs($user)
        ->get('http://test-tenant.localhost/contracts?status=1');

    $response->assertStatus(200);

    $props     = $response->original->getData()['page']['props'];
    $contracts = $props['contracts'];

    expect($contracts)->toHaveCount(1);
    expect($contracts[0]['name'])->toBe('Draft Contract');
});

it('filters contracts by name and status combined', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    Contract::factory()->create([
        'name'             => 'Alpha Contract Draft',
        'status'           => 1,
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);

    Contract::factory()->create([
        'name'             => 'Alpha Contract Active',
        'status'           => 2,
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);
    Contract::factory()->create([
        'name'             => 'Beta Contract Active',
        'status'           => 2,
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);

    $user = User::factory()->create();

    $response = actingAs($user)
        ->get('http://test-tenant.localhost/contracts?search=Alpha&status=2');

    $response->assertStatus(200);

    $props     = $response->original->getData()['page']['props'];
    $contracts = $props['contracts'];

    expect($contracts)->toHaveCount(1);
    expect($contracts[0]['name'])->toBe('Alpha Contract Active');
});

it('returns empty when search matches nothing', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    Contract::factory()->create([
        'name'             => 'Contract',
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id
    ]);

    $user = User::factory()->create();

    $response = actingAs($user)
        ->get('http://test-tenant.localhost/contracts?search=NoMatch');

    $response->assertStatus(200);

    $contracts = $response->original->getData()['page']['props']['contracts'];

    expect($contracts)->toHaveCount(0);
});
