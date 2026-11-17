<?php

use App\Models\User;

it('shows the storefront home to guests', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee(__('storefront.home.title'));
});

it('shows the storefront home to authenticated customers', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertOk();
});
