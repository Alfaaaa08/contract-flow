<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ContractTypeSeeder extends Seeder {
    public function run(): void {
        $clients = ['Procurement', 'Real Estate', 'Lease Agreement', 'Master Service Agreement', 'Service Level Agreement'];
        
        foreach ($clients as $name) {
            \App\Models\ContractType::create([
                'name'      => $name,
                'tenant_id' => 'contractflow'
            ]);
        }
    }
}
