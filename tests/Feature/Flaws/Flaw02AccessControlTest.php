<?php

/*
 * Chapter 2.02. A player only touches their own creatures.
 */

use App\Models\Creature;
use App\Models\User;

beforeEach(function () {
    $this->braise = Creature::factory()->create(['name' => 'Braise', 'mood' => 50]);
    $this->alice = $this->braise->user;
    $this->mallory = User::factory()->create();
});

test('a player cannot open the edit page of someone else\'s creature', function () {
    $this->actingAs($this->mallory)
        ->get("/creatures/{$this->braise->id}/modifier")
        ->assertForbidden();
});

test('a player cannot rename someone else\'s creature', function () {
    $this->actingAs($this->mallory)
        ->patch("/creatures/{$this->braise->id}", ['name' => 'À Mallory'])
        ->assertForbidden();

    expect($this->braise->fresh()->name)->toBe('Braise');
});

test('a player cannot play with someone else\'s creature', function () {
    $this->actingAs($this->mallory)
        ->post("/creatures/{$this->braise->id}/jouer")
        ->assertForbidden();

    expect($this->braise->fresh()->mood)->toBe(50);
});

test('a player cannot put someone else\'s creature to sleep', function () {
    $this->actingAs($this->mallory)
        ->post("/creatures/{$this->braise->id}/dormir")
        ->assertForbidden();

    expect($this->braise->fresh()->energy)->toBe(50);
});

test('a player cannot release someone else\'s creature', function () {
    $this->actingAs($this->mallory)
        ->delete("/creatures/{$this->braise->id}")
        ->assertForbidden();

    expect($this->braise->fresh())->not->toBeNull();
});

test('the owner still can', function () {
    $this->actingAs($this->alice)
        ->get("/creatures/{$this->braise->id}/modifier")
        ->assertOk();
});
