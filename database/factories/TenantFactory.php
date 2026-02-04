<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory {
    protected $model = Tenant::class;

    public function definition(): array {
        $slug = 'tenant-' . Str::random(10);

        return [
            'id'   => $slug,
            'name' => 'Company ' . $slug,
        ];
    }
}
