<?php

/*
 * Chapter 2.03. The motto form changes the motto, and nothing else.
 */

use App\Models\User;
use Illuminate\Support\Arr;

test('extra fields sent with the motto are ignored', function () {
    $mallory = User::factory()->create(['name' => 'Mallory']);

    $before = Arr::except($mallory->fresh()->getAttributes(), ['motto', 'updated_at']);

    $this->actingAs($mallory)
        ->patch('/enclos', [
            'motto' => 'Riche et célèbre.',
            'balance' => 1000,
            'balance_cents' => 100000,
            'name' => 'Alice',
            'email_verified_at' => null,
        ])
        ->assertRedirect();

    $after = Arr::except($mallory->fresh()->getAttributes(), ['motto', 'updated_at']);

    expect($mallory->fresh()->motto)->toBe('Riche et célèbre.');
    expect($after)->toBe($before);
});
