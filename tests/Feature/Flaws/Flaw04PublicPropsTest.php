<?php

/*
 * Chapter 2.04. Everything a page receives can be read in the browser.
 */

use App\Models\Creature;
use App\Models\User;

test('the leaderboard only sends what it displays', function () {
    $alice = User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
    Creature::factory()->for($alice)->create(['name' => 'Braise']);

    $creatures = $this->actingAs(User::factory()->create())
        ->get('/classement')
        ->assertOk()
        ->inertiaProps('creatures');

    $sent = json_encode($creatures);

    expect($sent)
        ->toContain('Braise')
        ->toContain('Alice')
        ->not->toContain('alice@example.com')
        ->not->toContain('email')
        ->not->toContain('balance');
});
