<?php

/*
 * The game itself. These tests are green from the start and must stay green
 * while you fix the flaws.
 */

use App\Models\Creature;
use App\Models\GiftCode;
use App\Models\Item;
use App\Models\Message;
use App\Models\Purchase;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are sent to the login page', function (string $url) {
    $this->get($url)->assertRedirect('/login');
})->with(['/dashboard', '/creatures/nouvelle', '/classement', '/magasin']);

test('a player sees their own creatures', function () {
    $player = User::factory()->create();
    Creature::factory()->for($player)->count(2)->create();
    Creature::factory()->create();

    $this->actingAs($player)
        ->get('/dashboard')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('creatures/Index')
            ->has('creatures', 2));
});

test('a player can adopt a creature', function () {
    $player = User::factory()->create();

    $this->actingAs($player)
        ->post('/creatures', ['name' => 'Braise', 'species' => 'dragon'])
        ->assertRedirect('/dashboard');

    expect($player->creatures()->where('name', 'Braise')->exists())->toBeTrue();
});

test('a creature needs a name and a known species', function () {
    $this->actingAs(User::factory()->create())
        ->post('/creatures', ['name' => '', 'species' => 'unicorn'])
        ->assertSessionHasErrors(['name', 'species']);

    expect(Creature::count())->toBe(0);
});

test('a player can rename their creature', function () {
    $creature = Creature::factory()->create(['name' => 'Braise']);

    $this->actingAs($creature->user)
        ->patch("/creatures/{$creature->id}", ['name' => 'Flamme'])
        ->assertRedirect("/creatures/{$creature->id}/modifier");

    expect($creature->fresh()->name)->toBe('Flamme');
});

test('a player can play with their creature', function () {
    $creature = Creature::factory()->create(['food' => 50, 'mood' => 50, 'energy' => 50]);

    $this->actingAs($creature->user)
        ->post("/creatures/{$creature->id}/jouer")
        ->assertRedirect();

    expect($creature->fresh()->only('food', 'mood', 'energy'))
        ->toBe(['food' => 40, 'mood' => 65, 'energy' => 40]);
});

test('a player can put their creature to sleep', function () {
    $creature = Creature::factory()->create(['food' => 50, 'mood' => 50, 'energy' => 50]);

    $this->actingAs($creature->user)
        ->post("/creatures/{$creature->id}/dormir")
        ->assertRedirect();

    expect($creature->fresh()->only('food', 'mood', 'energy'))
        ->toBe(['food' => 45, 'mood' => 50, 'energy' => 75]);
});

test('a player can release their creature', function () {
    $creature = Creature::factory()->create();

    $this->actingAs($creature->user)
        ->delete("/creatures/{$creature->id}")
        ->assertRedirect('/dashboard');

    expect(Creature::count())->toBe(0);
});

test('a player can visit the enclosure of another player', function () {
    $alice = User::factory()->create(['name' => 'Alice']);
    Creature::factory()->for($alice)->create(['name' => 'Braise']);

    $this->actingAs(User::factory()->create())
        ->get("/enclos/{$alice->id}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('enclosures/Show')
            ->where('owner.name', 'Alice')
            ->where('isOwner', false)
            ->where('creatures.0.name', 'Braise'));
});

test('a player can leave a message in a guest book', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    $this->actingAs($bob)
        ->post("/enclos/{$alice->id}/messages", ['body' => 'Bel enclos !'])
        ->assertRedirect("/enclos/{$alice->id}");

    $message = Message::sole();

    expect($message->body)->toBe('Bel enclos !');
    expect($message->author_id)->toBe($bob->id);
    expect($message->recipient_id)->toBe($alice->id);
});

test('a player can change their motto', function () {
    $player = User::factory()->create();

    $this->actingAs($player)
        ->patch('/enclos', ['motto' => 'Toujours de bonne humeur.'])
        ->assertRedirect("/enclos/{$player->id}");

    expect($player->fresh()->motto)->toBe('Toujours de bonne humeur.');
});

test('the leaderboard starts with the happiest creature', function () {
    Creature::factory()->create(['name' => 'Grognon', 'food' => 10, 'mood' => 10, 'energy' => 10]);
    Creature::factory()->create(['name' => 'Radieuse', 'food' => 90, 'mood' => 90, 'energy' => 90]);

    $this->actingAs(User::factory()->create())
        ->get('/classement')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Leaderboard')
            ->where('creatures.0.name', 'Radieuse')
            ->where('creatures.1.name', 'Grognon'));
});

test('the shop lists the items and finds one by its name', function () {
    Item::factory()->create(['name' => 'Croquettes']);
    Item::factory()->create(['name' => 'Balle rebondissante']);

    $player = User::factory()->create();

    $this->actingAs($player)
        ->get('/magasin')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('shop/Index')
            ->has('items', 2));

    $this->actingAs($player)
        ->get('/magasin?q=Balle')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.name', 'Balle rebondissante'));
});

test('a player can buy an item for their creature', function () {
    $creature = Creature::factory()->create(['food' => 50]);
    $item = Item::factory()->create(['effect' => 'food', 'effect_value' => 20, 'stock' => 5]);

    // The form sends the displayed price for as long as the shop asks for it.
    $this->actingAs($creature->user)
        ->post("/magasin/{$item->id}/acheter", [
            'creature_id' => $creature->id,
            'quantity' => 1,
            'price' => $item->getAttribute('price'),
            'price_cents' => $item->getAttribute('price_cents'),
        ])
        ->assertRedirect('/magasin');

    expect($creature->fresh()->food)->toBe(70);
    expect($item->fresh()->stock)->toBe(4);
    expect(Purchase::count())->toBe(1);
});

test('a gift code works once', function () {
    $giftCode = GiftCode::factory()->create(['code' => '1234']);
    $player = User::factory()->create();

    $this->actingAs($player)
        ->post('/codes-cadeau', ['code' => '1234'])
        ->assertRedirect('/magasin');

    expect($giftCode->fresh()->redeemed_by)->toBe($player->id);

    $this->actingAs($player)
        ->post('/codes-cadeau', ['code' => '1234'])
        ->assertSessionHasErrors('code');
});
