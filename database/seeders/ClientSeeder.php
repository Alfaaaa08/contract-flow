<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder {

    public function run(): void {
        $clients = ['Global Corp', 'Supply Co', 'Real Estate Inc', 'Tech Solutions', 'Acme Corp'];

        foreach ($clients as $name) {
            \App\Models\Client::create([
                'name'      => $name,
                'tenant_id' => 'contractflow'
            ]);
        }
    }
}
