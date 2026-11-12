<?php

namespace Database\Factories;

use App\Models\Creature;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Creature>
 */
class CreatureFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->firstName(),
            'species' => fake()->randomElement(Creature::SPECIES),
            'food' => 50,
            'mood' => 50,
            'energy' => 50,
        ];
    }
}
