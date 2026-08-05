<?php

use App\Models\User;
use App\Support\Roles;

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

it('checks roles passed as an array', function () {
    $user = User::factory()->create(['role' => Roles::MANAGER]);

    expect($user->hasRole([Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER]))->toBeTrue()
        ->and($user->hasRole([Roles::CLERK]))->toBeFalse();
});

it('redirects unauthenticated users from the dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});
