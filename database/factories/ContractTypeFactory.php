<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractType>
 */
class ContractTypeFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => $this->faker->randomElement(['Software', 'Service', 'Hardware']),
            'icon' => 'file-text',
            'tenant_id' => Tenant::factory(),
        ];
    }
}
