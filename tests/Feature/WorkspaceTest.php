<?php

use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitationMail;

beforeEach(function () {
    Mail::fake();
    $this->owner = makeUser();
    $this->actingAs($this->owner);
});

it('shows the members page', function () {
    $this->get('/workspace/members')->assertOk()->assertSee($this->owner->name);
});

it('creates an invitation and queues the email', function () {
    $this->post('/workspace/invite', [
        'email' => 'newbie@example.com',
        'role' => 'member',
    ])->assertRedirect();

    $inv = TeamInvitation::latest()->first();
    expect($inv)
        ->email->toBe('newbie@example.com')
        ->role->toBe('member')
        ->team_id->toBe($this->owner->current_team_id);

    Mail::assertQueued(TeamInvitationMail::class);
});

it('blocks duplicate pending invites', function () {
    TeamInvitation::create([
        'team_id' => $this->owner->current_team_id,
        'invited_by_user_id' => $this->owner->id,
        'email' => 'newbie@example.com',
        'role' => 'member',
        'token' => str_repeat('a', 48),
        'expires_at' => now()->addDays(7),
    ]);

    $this->post('/workspace/invite', [
        'email' => 'newbie@example.com',
        'role' => 'member',
    ])->assertSessionHasErrors('email');
});

it('lets the right user accept an invitation', function () {
    $invitee = makeUser(['email' => 'invitee@example.com']);
    $inv = TeamInvitation::create([
        'team_id' => $this->owner->current_team_id,
        'invited_by_user_id' => $this->owner->id,
        'email' => 'invitee@example.com',
        'role' => 'admin',
        'token' => str_repeat('b', 48),
        'expires_at' => now()->addDays(7),
    ]);

    $this->actingAs($invitee);
    $this->post(route('invitations.accept.post', $inv->token))->assertRedirect('/dashboard');

    expect($invitee->fresh()->current_team_id)->toBe($this->owner->current_team_id);
    expect($inv->fresh()->accepted_at)->not->toBeNull();
});

it('lets the owner remove a member', function () {
    $member = makeUser();
    $this->owner->currentTeam->members()->attach($member->id, ['role' => 'member']);

    $this->delete(route('workspace.members.remove', $member))->assertRedirect();
    expect($this->owner->currentTeam->members()->where('users.id', $member->id)->exists())->toBeFalse();
});

it('switches the current team', function () {
    $other = Team::create(['owner_id' => $this->owner->id, 'name' => 'Other', 'personal' => false]);
    $this->post(route('workspace.switch', $other))->assertRedirect();
    expect($this->owner->fresh()->current_team_id)->toBe($other->id);
});
