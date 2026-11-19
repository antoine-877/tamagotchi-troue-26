<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'effect' => 'food',
            'effect_value' => 10,
            'price_cents' => 2.50,
            'stock' => 20,
        ];
    }
}
