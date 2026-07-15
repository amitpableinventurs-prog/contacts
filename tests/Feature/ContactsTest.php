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
        'phone' => '9998887776',
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

it('shows banned contacts in clerk search and admin search', function () {
    Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'name'    => 'Blocked Person',
        'phone'   => '9503466923',
        'status'  => 'banned',
    ]);

    $clerk = makeClerkOnTeam($this->user->current_team_id);

    // Clerk: banned contacts are visible and clearly marked.
    $this->actingAs($clerk);
    $this->get('/contacts?number=9503466923')->assertOk()->assertSee('Blocked Person')->assertSee('BANNED');
    expect($this->getJson('/contacts/autocomplete?q=9503466923')->json())->toHaveCount(1);

    // Admin still finds the banned contact to manage the blacklist.
    $this->actingAs($this->user);
    $this->get('/contacts?number=9503466923')->assertOk()->assertSee('Blocked Person');
});

it('shows the saved address in the edit form textarea', function () {
    $c = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'address' => '12 Main Road, Kothrud',
    ]);
    $this->get(route('contacts.edit', $c))->assertOk()->assertSee('12 Main Road, Kothrud');
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

it('lets super admin set the comment and shows it to every role', function () {
    $sa = makeUser(['role' => \App\Support\Roles::SUPER_ADMIN]);
    $this->actingAs($sa);
    $contact = Contact::factory()->create(['team_id' => $sa->current_team_id]);

    $this->put(route('contacts.update', $contact), [
        'name'          => $contact->name,
        'phone'         => '9503466923',
        'phone_country' => 'in',
        'admin_comment' => 'VIP customer — handle with care.',
    ])->assertRedirect();

    expect($contact->fresh()->admin_comment)->toBe('VIP customer — handle with care.');

    // A clerk (lowest role) sees the comment on the contact page.
    $clerk = makeClerkOnTeam($sa->current_team_id);
    $this->actingAs($clerk)
        ->get(route('contacts.show', $contact))
        ->assertOk()
        ->assertSee('VIP customer — handle with care.');
});

it('ignores the comment field for non-super-admin editors', function () {
    $contact = Contact::factory()->create([
        'team_id'       => $this->user->current_team_id,
        'admin_comment' => 'Original comment',
    ]);

    // $this->user is an Admin — may edit the contact but not the comment.
    $this->put(route('contacts.update', $contact), [
        'name'          => $contact->name,
        'phone'         => '9503466923',
        'admin_comment' => 'Overwritten comment',
    ])->assertRedirect();

    expect($contact->fresh()->admin_comment)->toBe('Original comment');
});

it('saves the phone country with the contact', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);

    $this->put(route('contacts.update', $contact), [
        'name'          => $contact->name,
        'phone'         => '4155551234',
        'phone_country' => 'us',
    ])->assertRedirect();

    expect($contact->fresh()->phone_country)->toBe('us');
});

it('hides notes from clerks and blocks them from adding notes', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $clerk = makeClerkOnTeam($this->user->current_team_id);

    $this->actingAs($clerk);

    // Clerk does not see the Notes tab or the add-note form.
    $this->get(route('contacts.show', $contact))->assertOk()->assertDontSee('Add note');

    $this->post(route('contacts.notes.store', $contact), [
        'note_html' => 'Spoke on the phone, call back tomorrow.',
    ])->assertForbidden();

    expect(\App\Models\ContactNote::where('contact_id', $contact->id)->count())->toBe(0);
});

it('lets manager-and-above roles add a note and records it in the activity log', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);

    $this->post(route('contacts.notes.store', $contact), [
        'note_html' => 'Spoke on the phone, call back tomorrow.',
    ])->assertRedirect();

    $note = \App\Models\ContactNote::where('contact_id', $contact->id)->firstOrFail();
    expect($note->user_id)->toBe($this->user->id)
        ->and(\App\Models\ActivityLog::where('user_id', $this->user->id)->where('action', 'note.added')->count())->toBe(1);
});

it('blocks clerks from deleting notes', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $note = \App\Models\ContactNote::create([
        'team_id'    => $contact->team_id,
        'contact_id' => $contact->id,
        'user_id'    => $this->user->id,
        'note_html'  => 'Manager note.',
    ]);

    $clerk = makeClerkOnTeam($this->user->current_team_id);

    $this->actingAs($clerk)
        ->delete(route('contacts.notes.destroy', [$contact, $note]))
        ->assertForbidden();
});

it('logs note deletion by managers and above', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $note = \App\Models\ContactNote::create([
        'team_id'    => $contact->team_id,
        'contact_id' => $contact->id,
        'user_id'    => $this->user->id,
        'note_html'  => 'Old note.',
    ]);

    $this->delete(route('contacts.notes.destroy', [$contact, $note]))->assertRedirect();

    expect(\App\Models\ContactNote::find($note->id))->toBeNull()
        ->and(\App\Models\ActivityLog::where('action', 'note.deleted')->count())->toBe(1);
});
