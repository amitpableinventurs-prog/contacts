<?php

use App\Models\Contact;
use App\Models\Group;
use App\Models\Tag;

beforeEach(function () {
    $this->user = makeUser();
    $this->actingAs($this->user);
});

it('lists contacts for the current team', function () {
    Contact::factory()->count(3)->create(['team_id' => $this->user->current_team_id]);
    // Another team's contacts must NOT appear.
    $other = makeUser();
    Contact::factory()->count(2)->create(['team_id' => $other->current_team_id]);

    $this->get('/contacts')
        ->assertOk()
        ->assertSee('3 contacts');
});

it('creates a contact via the form', function () {
    $this->post('/contacts', [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'phone' => '+15551112222',
    ])->assertRedirect();

    $contact = Contact::where('email', 'ada@example.com')->firstOrFail();
    expect($contact)
        ->team_id->toBe($this->user->current_team_id)
        ->owner_id->toBe($this->user->id);
});

it('shows a contact', function () {
    $c = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $this->get(route('contacts.show', $c))->assertOk()->assertSee($c->name);
});

it('hides contacts from another team (404 via global scope)', function () {
    $other = makeUser();
    $foreign = Contact::factory()->create(['team_id' => $other->current_team_id]);
    // Global team scope means the contact effectively does not exist for this user.
    $this->get('/contacts/'.$foreign->id)->assertNotFound();
});

it('updates a contact', function () {
    $c = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $this->put(route('contacts.update', $c), [
        'name' => 'Renamed',
        'email' => $c->email,
    ])->assertRedirect();
    expect($c->fresh()->name)->toBe('Renamed');
});

it('soft-deletes a contact', function () {
    $c = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $this->delete(route('contacts.destroy', $c))->assertRedirect();
    expect($c->fresh()->trashed())->toBeTrue();
});

it('returns autocomplete results scoped to team', function () {
    Contact::factory()->create(['team_id' => $this->user->current_team_id, 'name' => 'Grace Hopper']);
    Contact::factory()->create(['team_id' => makeUser()->current_team_id, 'name' => 'Grace Other']);

    $res = $this->getJson('/contacts/autocomplete?q=Grace');
    $res->assertOk();
    expect($res->json())->toHaveCount(1)
        ->and($res->json('0.name'))->toBe('Grace Hopper');
});

it('bulk-deletes selected contacts', function () {
    $cs = Contact::factory()->count(3)->create(['team_id' => $this->user->current_team_id]);
    $this->post('/contacts/bulk', [
        'action' => 'delete',
        'contact_ids' => $cs->pluck('id')->all(),
    ])->assertRedirect();
    expect(Contact::count())->toBe(0);
});

it('bulk-assigns contacts to a group', function () {
    $group = Group::create(['team_id' => $this->user->current_team_id, 'name' => 'VIP']);
    $cs = Contact::factory()->count(2)->create(['team_id' => $this->user->current_team_id]);

    $this->post('/contacts/bulk', [
        'action' => 'group',
        'group_id' => $group->id,
        'contact_ids' => $cs->pluck('id')->all(),
    ])->assertRedirect();

    expect(Contact::where('group_id', $group->id)->count())->toBe(2);
});

it('detects duplicate contacts by email', function () {
    $a = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'same@example.com']);
    Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'same@example.com']);
    expect($a->potentialDuplicates()->count())->toBe(1);
});

it('merges duplicates and reassigns activity', function () {
    $keep = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'same@example.com', 'company' => null]);
    $dup = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'same@example.com', 'company' => 'Acme']);

    $this->post(route('contacts.merge.store', $keep), [
        'duplicate_ids' => [$dup->id],
    ])->assertRedirect();

    $keep->refresh();
    expect($keep->company)->toBe('Acme');
    expect(Contact::find($dup->id))->toBeNull();
});
