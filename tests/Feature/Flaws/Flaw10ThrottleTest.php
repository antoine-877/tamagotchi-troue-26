<?php

/*
 * Chapter 2.10. Nobody tries a thousand gift codes in a minute.
 */

use App\Models\User;

test('the sixth gift code tried within a minute is refused', function () {
    $mallory = User::factory()->create();

    foreach (['0001', '0002', '0003', '0004', '0005'] as $code) {
        $this->actingAs($mallory)
            ->post('/codes-cadeau', ['code' => $code])
            ->assertSessionHasErrors('code');
    }

    $this->actingAs($mallory)
        ->post('/codes-cadeau', ['code' => '0006'])
        ->assertTooManyRequests();
});
