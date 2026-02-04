<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ContractType;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory {
    public function definition(): array {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->words(3, true),
            'client_id' => Client::factory(),
            'contract_type_id' => ContractType::factory(), 
            'value' => $this->faker->randomFloat(2, 500, 10000),
            'status' => 1,
            'end_date' => now()->addYear(),
        ];
    }

    public function active(): static {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
            'end_date' => now()->addMonths(6),
        ]);
    }

    public function expiring(): static {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
            'end_date' => now()->addDays(15),
        ]);
    }
}