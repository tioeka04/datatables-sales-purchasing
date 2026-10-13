<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $purchase = $this->faker->numberBetween(5, 5000) * 1000;
        $margin = $this->faker->randomFloat(2, 1.1, 1.5);

        return [
            'product_code' => strtoupper($this->faker->unique()->bothify('PRD-#####')),
            'product_name' => ucwords($this->faker->words($this->faker->numberBetween(2, 4), true)),
            'category' => $this->faker->randomElement(Product::CATEGORIES),
            'supplier_id' => null,
            'purchase_price' => $purchase,
            'price' => round($purchase * $margin, -2),
            'stock' => $this->faker->numberBetween(0, 500),
            'description' => $this->faker->optional(0.6)->sentence(),
        ];
    }
}
