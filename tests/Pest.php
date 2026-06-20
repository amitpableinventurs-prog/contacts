<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/**
 * Create a user. The UserObserver auto-creates their personal team.
 */
function makeUser(array $attrs = []): \App\Models\User
{
    return \App\Models\User::factory()->create($attrs)->fresh();
}
