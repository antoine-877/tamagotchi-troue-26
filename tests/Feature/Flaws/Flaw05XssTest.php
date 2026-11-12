<?php

/*
 * Chapter 2.05. A message is text, never code.
 */

test('the guest book does not display messages as HTML', function () {
    $page = file_get_contents(resource_path('js/pages/enclosures/Show.vue'));

    expect($page)->not->toContain('v-html');
});
