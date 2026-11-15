<?php

it('redirects guests from home to the login page', function () {
    $this->get('/')
        ->assertRedirect(route('login'));
});

it('shows the dashboard to authenticated users', function () {
    actingAsAdmin();

    $this->get('/')
        ->assertOk();
});
