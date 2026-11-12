<?php

/*
 * Chapter 2.09. A purchase happens entirely, or not at all.
 */

use App\Models\Creature;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;

beforeEach(function () {
    $this->player = User::factory()->create(['balance_cents' => 1000]);
    $this->creature = Creature::factory()->for($this->player)->create(['food' => 50]);
    $this->item = Item::factory()->create(['price_cents' => 250, 'stock' => 5, 'effect' => 'food', 'effect_value' => 20]);
});

afterEach(function () {
    Purchase::flushEventListeners();
});

test('a purchase that breaks halfway changes nothing', function () {
    // The breakdown: the server stops at the moment it writes the purchase.
    Purchase::creating(function () {
        throw new RuntimeException('Panne au milieu de l\'achat.');
    });

    $this->actingAs($this->player)
        ->post("/magasin/{$this->item->id}/acheter", [
            'creature_id' => $this->creature->id,
            'quantity' => 2,
        ])
        ->assertServerError();

    expect($this->player->fresh()->balance_cents)->toBe(1000);
    expect($this->item->fresh()->stock)->toBe(5);
    expect($this->creature->fresh()->food)->toBe(50);
    expect(Purchase::count())->toBe(0);
});

test('a player cannot spend more than their balance', function () {
    $this->actingAs($this->player)
        ->post("/magasin/{$this->item->id}/acheter", [
            'creature_id' => $this->creature->id,
            'quantity' => 5,
        ])
        ->assertSessionHasErrors('quantity');

    expect($this->player->fresh()->balance_cents)->toBe(1000);
    expect($this->item->fresh()->stock)->toBe(5);
});

test('a player cannot buy more than the stock', function () {
    $this->item->update(['stock' => 1]);

    $this->actingAs($this->player)
        ->post("/magasin/{$this->item->id}/acheter", [
            'creature_id' => $this->creature->id,
            'quantity' => 2,
        ])
        ->assertSessionHasErrors('quantity');

    expect($this->player->fresh()->balance_cents)->toBe(1000);
    expect($this->item->fresh()->stock)->toBe(1);
});
