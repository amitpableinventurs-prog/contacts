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

/**
 * Create a clerk on an existing team. The UserObserver always parks new
 * users on a fresh personal team, so join + switch afterwards — the same
 * thing UsersController::store does for accounts created via "Add user".
 */
function makeClerkOnTeam(int $teamId): \App\Models\User
{
    $clerk = \App\Models\User::factory()->create(['role' => \App\Support\Roles::CLERK]);
    $clerk->teams()->syncWithoutDetaching([$teamId => ['role' => 'member']]);
    $clerk->forceFill(['current_team_id' => $teamId])->save();

    return $clerk->fresh();
}
