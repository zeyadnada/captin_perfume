<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/**
 * Authenticate as a fresh admin user for protected route tests.
 */
function actingAsAdmin(): User
{
    $user = User::factory()->create();

    test()->actingAs($user);

    return $user;
}
