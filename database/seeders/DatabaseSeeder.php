<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            AdminSeeder::class,
            DemoTenantSeeder::class,
            ClientSeeder::class,
            ContractTypeSeeder::class
        ]);
    }
}
