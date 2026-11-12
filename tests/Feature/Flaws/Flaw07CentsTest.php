<?php

/*
 * Chapter 2.07. An amount of money is a whole number of cents.
 */

use App\Models\Creature;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

test('amounts are stored in cents', function () {
    expect(Schema::hasColumn('users', 'balance_cents'))->toBeTrue();
    expect(Schema::hasColumn('items', 'price_cents'))->toBeTrue();
    expect(Schema::hasColumn('purchases', 'total_cents'))->toBeTrue();
    expect(Schema::hasColumn('gift_codes', 'amount_cents'))->toBeTrue();

    expect(Schema::hasColumn('users', 'balance'))->toBeFalse();
    expect(Schema::hasColumn('items', 'price'))->toBeFalse();
    expect(Schema::hasColumn('purchases', 'total'))->toBeFalse();
    expect(Schema::hasColumn('gift_codes', 'amount'))->toBeFalse();
});

test('a new player starts with 50 €', function () {
    $player = User::factory()->create();

    expect($player->fresh()->balance_cents)->toBe(5000);
});

test('three sweets at 0,10 € cost exactly 0,30 €', function () {
    $player = User::factory()->create(['balance_cents' => 30]);
    $creature = Creature::factory()->for($player)->create();
    $sweet = Item::factory()->create(['price_cents' => 10, 'stock' => 10]);

    $this->actingAs($player)
        ->post("/magasin/{$sweet->id}/acheter", [
            'creature_id' => $creature->id,
            'quantity' => 3,
            'price_cents' => 10,
        ])
        ->assertSessionHasNoErrors();

    expect($player->fresh()->balance_cents)->toBe(0);
    expect(Purchase::sole()->total_cents)->toBe(30);
});

test('the shop displays prices in euros', function () {
    Item::factory()->create(['price_cents' => 250]);

    $items = $this->actingAs(User::factory()->create())
        ->get('/magasin')
        ->assertOk()
        ->inertiaProps('items');

    expect(json_encode($items))->toContain('2,50');
});
