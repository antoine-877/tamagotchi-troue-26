<?php

/*
 * Chapter 2.06. What the player types is a value, never a piece of SQL.
 */

use App\Models\Item;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Item::factory()->create(['name' => 'Croquettes']);
    Item::factory()->create(['name' => 'L\'os à mâcher']);
});

test('a search with an apostrophe works', function () {
    $this->actingAs(User::factory()->create())
        ->get('/magasin?q='.urlencode('L\'os'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.name', 'L\'os à mâcher'));
});

test('a search cannot rewrite the query', function () {
    $this->actingAs(User::factory()->create())
        ->get('/magasin?q='.urlencode("zzz%' OR '1'='1' --"))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->has('items', 0));
});
