<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_code' => strtoupper($this->faker->unique()->bothify('SUP-####')),
            'name' => 'PT '.$this->faker->company(),
            'phone' => $this->faker->numerify('08##########'),
        ];
    }
}
