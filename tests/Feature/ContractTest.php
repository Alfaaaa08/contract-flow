<?php

use App\Models\Contract;
use App\Models\User;
use App\Models\Tenant;
use App\Models\ContractType;
use App\Models\Client;
use function Pest\Laravel\actingAs;


it('filters contracts by name', function () {
    $tenant = Tenant::factory()->create();

    $domain = "{$tenant->id}.localhost";
    $tenant->domains()->create(['domain' => $domain]);

    tenancy()->initialize($tenant);

    $client = Client::factory()->create([
        'name' => 'Alpha Client',
        'tenant_id' => $tenant->id
    ]);

    $type = ContractType::factory()->create([
        'name' => 'Procurement',
        'tenant_id' => $tenant->id
    ]);

    Contract::factory()->create([
        'name' => 'Alpha Project',
        'client_id' => $client->id,
        'contract_type_id' => $type->id,
    ]);
    
    Contract::factory()->create([
        'name' => 'Beta Project',
        'client_id' => $client->id,
        'contract_type_id' => $type->id,
    ]);
    
    $user = User::factory()->create();

    $response = actingAs($user)
        ->get("http://{$tenant->id}.localhost/contracts?search=Alpha");
    
    $response->assertStatus(200);
});
