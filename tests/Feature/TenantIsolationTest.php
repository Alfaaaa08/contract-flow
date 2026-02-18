<?php

use App\Models\Contract;
use App\Models\User;
use App\Models\ContractType;
use App\Models\Client;
use function Pest\Laravel\actingAs;

it('tenant A cannot see tenant B contracts', function () {
	$tenantA = createTenant('tenant-a');
	$clientA = Client::factory()->create(['name' => 'Client A', 'tenant_id' => $tenantA->id]);
    $typeA   = ContractType::factory()->create(['name' => 'Type A', 'tenant_id' => $tenantA->id]);

	Contract::factory()->count(2)->create([
        'name'             => 'Contract A',
        'client_id'        => $clientA->id,
        'contract_type_id' => $typeA->id,
        'tenant_id'        => $tenantA->id
    ]);

	$userA = User::factory()->create();

	$tenantB = createTenant('tenant-b');
	$clientB = Client::factory()->create(['name' => 'Client B', 'tenant_id' => $tenantB->id]);
    $typeB   = ContractType::factory()->create(['name' => 'Type B', 'tenant_id' => $tenantB->id]);

	Contract::factory()->count(2)->create([
        'name'             => 'Contract b',
        'client_id'        => $clientB->id,
        'contract_type_id' => $typeB->id,
        'tenant_id'        => $tenantB->id
    ]);

	$userB = User::factory()->create();

	$responseA  = actingAs($userA)->get('http://tenant-a.localhost/contracts');
	$contractsA = $responseA->original->getData()['page']['props']['contracts'];

	expect($contractsA)->toHaveCount(2);

	$responseB  = actingAs($userB)->get('http://tenant-b.localhost/contracts');
	$contractsB = $responseB->original->getData()['page']['props']['contracts'];

	expect($contractsB)->toHaveCount(2);
});