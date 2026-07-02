<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/**
 * Create a user. The UserObserver auto-creates their personal team.
 * Defaults to admin so tests exercise the full feature set — the DB
 * default role is clerk, which is restricted to phone-number search.
 */
function makeUser(array $attrs = []): \App\Models\User
{
    return \App\Models\User::factory()->create($attrs + ['role' => \App\Support\Roles::ADMIN])->fresh();
}
