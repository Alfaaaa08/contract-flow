<?php

use App\Models\Contract;
use App\Models\User;
use App\Models\Tenant;
use App\Models\ContractType;
use App\Models\Client;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\DB;


it('filters contracts by name', function () {
    $tenant = createTenant('test-tenant');

    $client = Client::factory()->create(['name' => 'Alpha Client', 'tenant_id' => $tenant->id]);
    $type   = ContractType::factory()->create(['name' => 'Procurement', 'tenant_id' => $tenant->id]);

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

    dump([
        'BEFORE REQUEST' => [
            'contracts_count'    => Contract::count(),
            'db_connection'      => DB::connection()->getName(),
            'db_file'            => DB::connection()->getDatabaseName(),
            'raw_count'          => DB::select('SELECT COUNT(*) as total FROM contracts'),
            'contracts_ids'      => Contract::pluck('id')->toArray(),
            'contracts_names'    => Contract::pluck('name')->toArray(),
        ]
    ]);


    $response = actingAs($user)
        ->get('http://test-tenant.localhost/contracts?search=Alpha');

    $response->assertStatus(200);

    $props     = $response->original->getData()['page']['props'];
    $contracts = $props['contracts'];
});
