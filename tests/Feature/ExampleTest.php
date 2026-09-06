<?php

it('redirects root to login or dashboard', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
});
