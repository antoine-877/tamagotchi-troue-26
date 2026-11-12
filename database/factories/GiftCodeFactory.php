<?php

namespace Database\Factories;

use App\Models\GiftCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GiftCode>
 */
class GiftCodeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->numerify('####'),
            'amount' => 5,
        ];
    }
}
