<?php

namespace Database\Seeders;

use App\Models\Creature;
use App\Models\GiftCode;
use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Every account uses the password "password".
     */
    public function run(): void
    {
        $alice = User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'motto' => 'Un enclos propre, des créatures heureuses.',
            'balance_cents' => 12000,
        ]);

        $mallory = User::factory()->create([
            'name' => 'Mallory',
            'email' => 'mallory@example.com',
            'motto' => 'Je regarde, c\'est tout.',
            'balance_cents' => 500,
        ]);

        $bob = User::factory()->create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'balance_cents' => 30,
        ]);

        Creature::factory()->for($alice)->create(['name' => 'Braise', 'species' => 'dragon', 'food' => 90, 'mood' => 95, 'energy' => 80]);
        Creature::factory()->for($alice)->create(['name' => 'Moustache', 'species' => 'cat', 'food' => 70, 'mood' => 85, 'energy' => 60]);
        Creature::factory()->for($mallory)->create(['name' => 'Encre', 'species' => 'octopus', 'food' => 30, 'mood' => 20, 'energy' => 40]);
        Creature::factory()->for($bob)->create(['name' => 'Roux', 'species' => 'fox', 'food' => 60, 'mood' => 70, 'energy' => 75]);
        Creature::factory()->for($bob)->create(['name' => 'Noisette', 'species' => 'rabbit', 'food' => 55, 'mood' => 65, 'energy' => 90]);

        Message::factory()->create([
            'author_id' => $bob->id,
            'recipient_id' => $alice->id,
            'body' => 'Braise est magnifique, bravo !',
        ]);

        Item::factory()->create(['name' => 'Bonbon', 'description' => 'Une petite douceur.', 'effect' => 'mood', 'effect_value' => 2, 'price_cents' => 10, 'stock' => 200]);
        Item::factory()->create(['name' => 'Croquettes', 'description' => 'Le repas de tous les jours.', 'effect' => 'food', 'effect_value' => 20, 'price_cents' => 250, 'stock' => 50]);
        Item::factory()->create(['name' => 'Festin', 'description' => 'Un repas de fête, pour une créature affamée.', 'effect' => 'food', 'effect_value' => 60, 'price_cents' => 690, 'stock' => 10]);
        Item::factory()->create(['name' => 'Balle rebondissante', 'description' => 'Des heures de jeu.', 'effect' => 'mood', 'effect_value' => 25, 'price_cents' => 320, 'stock' => 30]);
        Item::factory()->create(['name' => 'L\'os à mâcher', 'description' => 'Solide, et il occupe longtemps.', 'effect' => 'mood', 'effect_value' => 15, 'price_cents' => 180, 'stock' => 40]);
        Item::factory()->create(['name' => 'Boisson vitaminée', 'description' => 'Un coup de fouet.', 'effect' => 'energy', 'effect_value' => 40, 'price_cents' => 450, 'stock' => 25]);
        Item::factory()->create(['name' => 'Coussin doré', 'description' => 'Une sieste de roi. Il n\'en reste presque plus.', 'effect' => 'energy', 'effect_value' => 80, 'price_cents' => 1990, 'stock' => 1]);

        GiftCode::factory()->create(['code' => '4821', 'amount_cents' => 5]);
        GiftCode::factory()->create(['code' => '7305', 'amount_cents' => 10]);
        GiftCode::factory()->create(['code' => '1996', 'amount_cents' => 25]);
    }
}
