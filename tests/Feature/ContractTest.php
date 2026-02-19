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


// CREATE

it('creates a contract with valid data', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    $user   = User::factory()->create();

    actingAs($user)
        ->post('http://test-tenant.localhost/contracts', [
            'name'             => 'New Contract',
            'client_id'        => $client->id,
            'contract_type_id' => $type->id,
            'start_date'       => '2026-03-01',
            'end_date'         => '2026-12-31',
            'value'            => 5000,
        ])
        ->assertStatus(302);

    expect(Contract::where('name', 'New Contract')->exists())->toBeTrue();
    expect(Contract::count())->toBe(1);
});

it('cannot create contract without required fields', function () {
    $tenant = createTenant('test-tenant');
    $user   = User::factory()->create();

    actingAs($user)
        ->post('http://test-tenant.localhost/contracts', [])
        ->assertStatus(302)
        ->assertSessionHasErrors(['name', 'client_id', 'contract_type_id']);

    expect(Contract::count())->toBe(0);
});

it('cannot create contract with end date before start date', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    $user   = User::factory()->create();

    actingAs($user)
        ->post('http://test-tenant.localhost/contracts', [
            'name'             => 'New Contract',
            'client_id'        => $client->id,
            'contract_type_id' => $type->id,
            'start_date'       => '2026-12-01',
            'end_date'         => '2026-10-01',
            'value'            => 5000,
        ])
        ->assertStatus(302)
        ->assertSessionHasErrors(['end_date']);

    expect(Contract::count())->toBe(0);
});

it('cannot create contract with invalid client', function () {
    $tenant = createTenant('test-tenant');

    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    $user   = User::factory()->create();

    $response = actingAs($user)
        ->post('http://test-tenant.localhost/contracts', [
            'name'             => 'New Contract',
            'client_id'        => 99999,
            'contract_type_id' => $type->id,
            'start_date'       => '2026-08-01',
            'end_date'         => '2026-10-01',
            'value'            => 5000,
        ])
        ->assertStatus(302)
        ->assertSessionHasErrors(['client_id']);
});

// UPDATE

it('updates all columns from a contract with valid data', function () {
    $tenant = createTenant('test-tenant');

    $clientA = Client::factory()->create(['name' => 'Client A', 'tenant_id' => $tenant->id]);
    $clientB = Client::factory()->create(['name' => 'Client B', 'tenant_id' => $tenant->id]);
    $typeA   = ContractType::factory()->create(['name' => 'Type B', 'tenant_id' => $tenant->id]);
    $typeB   = ContractType::factory()->create(['name' => 'Type B', 'tenant_id' => $tenant->id]);

    $user   = User::factory()->create();

    $contract = Contract::factory()->create([
        'name'             => 'Contract Name',
        'client_id'        => $clientA->id,
        'contract_type_id' => $typeA->id,
        'tenant_id'        => $tenant->id,
        'status'           => 1,
        'start_date'       => '2026-05-15',
        'end_date'         => '2026-05-15',
        'value'            => 1111,
    ]);


    actingAs($user)
        ->put("http://test-tenant.localhost/contracts/{$contract->id}", [
            'name'             => 'Updated Name',
            'client_id'        => $clientB->id,
            'contract_type_id' => $typeB->id,
            'start_date'       => '2026-06-01',
            'end_date'         => '2026-06-15',
            'value'            => 9999,
        ])
        ->assertStatus(302);

    expect($contract->fresh()->name)->toBe('Updated Name');
    expect($contract->fresh()->value)->toBe('9999.00');
});

// DELETE

it('deletes a contract', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    $user   = User::factory()->create();

    $contract = Contract::factory()->create([
        'name'             => 'Contract',
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id,
    ]);


    actingAs($user)
        ->delete("http://test-tenant.localhost/contracts/{$contract->id}")
        ->assertStatus(302);

    expect(Contract::count())->toBe(0);
});

it('bulk deletes multiple contracts', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Type', 'tenant_id' => $tenant->id]);

    $user   = User::factory()->create();

    $contractA = Contract::factory()->create([
        'name'             => 'Contract Alpha',
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id,
    ]);

    $contractB = Contract::factory()->create([
        'name'             => 'Contract Beta',
        'client_id'        => $client->id,
        'contract_type_id' => $type->id,
        'tenant_id'        => $tenant->id,
    ]);


    actingAs($user)
        ->delete("http://test-tenant.localhost/contracts/bulk-destroy", [
            'ids' => [
                $contractA->id, $contractB->id
            ]
        ])
        ->assertStatus(302);

    expect(Contract::count())->toBe(0);
});
