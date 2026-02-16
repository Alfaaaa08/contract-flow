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
