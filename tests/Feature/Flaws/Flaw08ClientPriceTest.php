<?php

/*
 * Chapter 2.08. The server knows the price. It never asks the browser.
 */

use App\Models\Creature;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;

beforeEach(function () {
    $this->mallory = User::factory()->create(['balance_cents' => 5000]);
    $this->creature = Creature::factory()->for($this->mallory)->create();
    $this->item = Item::factory()->create(['price_cents' => 1990, 'stock' => 5]);
});

test('a price sent by the browser is ignored', function () {
    $this->actingAs($this->mallory)
        ->post("/magasin/{$this->item->id}/acheter", [
            'creature_id' => $this->creature->id,
            'quantity' => 1,
            'price' => 0.01,
            'price_cents' => 1,
        ])
        ->assertSessionHasNoErrors();

    expect($this->mallory->fresh()->balance_cents)->toBe(5000 - 1990);
    expect(Purchase::sole()->total_cents)->toBe(1990);
});

test('the quantity is a whole number from 1 to 10', function (mixed $quantity) {
    $this->actingAs($this->mallory)
        ->post("/magasin/{$this->item->id}/acheter", [
            'creature_id' => $this->creature->id,
            'quantity' => $quantity,
            'price_cents' => 1990,
        ])
        ->assertSessionHasErrors('quantity');

    expect($this->mallory->fresh()->balance_cents)->toBe(5000);
    expect($this->item->fresh()->stock)->toBe(5);
    expect(Purchase::count())->toBe(0);
})->with([-5, 0, 11, 'beaucoup']);

test('a player only buys for their own creature', function () {
    $braise = Creature::factory()->create();

    $this->actingAs($this->mallory)
        ->post("/magasin/{$this->item->id}/acheter", [
            'creature_id' => $braise->id,
            'quantity' => 1,
            'price_cents' => 1990,
        ])
        ->assertSessionHasErrors('creature_id');

    expect($this->mallory->fresh()->balance_cents)->toBe(5000);
    expect(Purchase::count())->toBe(0);
});
