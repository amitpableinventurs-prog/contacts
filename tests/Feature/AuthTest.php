<?php

use App\Models\Team;
use App\Models\User;

it('registers a user and auto-creates a personal team', function () {
    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    $user = User::where('email', 'jane@example.com')->firstOrFail();
    expect($user->current_team_id)->not->toBeNull();

    $team = Team::find($user->current_team_id);
    expect($team)
        ->name->toBe("Jane Doe's Workspace")
        ->personal->toBeTrue()
        ->owner_id->toBe($user->id);

    expect($user->teams()->count())->toBe(1);
});

it('logs a registered user in', function () {
    $user = User::factory()->create(['email' => 'demo@example.com']);

    $this->post('/login', [
        'email' => 'demo@example.com',
        'password' => 'password',
    ])->assertRedirect('/dashboard');

    $this->assertAuthenticatedAs($user);
});

it('rejects bad credentials', function () {
    User::factory()->create(['email' => 'demo@example.com']);
    $this->post('/login', ['email' => 'demo@example.com', 'password' => 'wrong'])
        ->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('logs the user out', function () {
    $this->actingAs(makeUser());
    $this->post('/logout')->assertRedirect('/');
    $this->assertGuest();
});

it('redirects unauthenticated users from the dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});
