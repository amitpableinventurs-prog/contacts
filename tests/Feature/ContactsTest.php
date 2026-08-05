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

it('requires approval when a manager adds a contact', function () {
    $manager = makeManagerOnTeam($this->user->current_team_id);
    $this->actingAs($manager);

    $this->post('/contacts', [
        'name' => 'Pending Manager Contact',
        'email' => 'manager-pending@example.com',
        'phone' => '+15550001111',
    ])->assertRedirect(route('contacts.index'));

    $contact = Contact::where('email', 'manager-pending@example.com')->firstOrFail();

    expect($contact->approval_status)->toBe('pending')
        ->and($contact->approved_by)->toBeNull()
        ->and($contact->approved_at)->toBeNull()
        ->and($contact->owner_id)->toBe($manager->id);

    session()->forget('toast');

    $this->get(route('contacts.index'))
        ->assertOk()
        ->assertSee('Pending Manager Contact');
});

it('hides manager pending contacts from admin super admin and clerk main lists', function () {
    $manager = makeManagerOnTeam($this->user->current_team_id);
    Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'owner_id' => $manager->id,
        'name' => 'Only Manager Pending',
        'phone' => '15550001112',
        'approval_status' => 'pending',
        'approved_by' => null,
        'approved_at' => null,
    ]);

    $this->get(route('contacts.index'))
        ->assertOk()
        ->assertDontSee('Only Manager Pending');

    $superAdmin = makeUser(['role' => \App\Support\Roles::SUPER_ADMIN]);
    $superAdmin->teams()->syncWithoutDetaching([$this->user->current_team_id => ['role' => 'member']]);
    $superAdmin->forceFill(['current_team_id' => $this->user->current_team_id])->save();

    $this->actingAs($superAdmin)
        ->get(route('contacts.index'))
        ->assertOk()
        ->assertDontSee('Only Manager Pending');

    $clerk = makeClerkOnTeam($this->user->current_team_id);

    $this->actingAs($clerk)
        ->get(route('contacts.index', ['number' => '15550001112']))
        ->assertOk()
        ->assertDontSee('Only Manager Pending');

    expect($this->getJson(route('contacts.autocomplete', ['q' => '15550001112']))->json())->toHaveCount(0);
});

it('lets admin approve a manager-created contact and removes it from manager list', function () {
    $manager = makeManagerOnTeam($this->user->current_team_id);
    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'owner_id' => $manager->id,
        'name' => 'Approval Needed',
        'email' => 'approval-needed@example.com',
        'approval_status' => 'pending',
        'approved_by' => null,
        'approved_at' => null,
    ]);

    $this->get(route('contacts.pending'))
        ->assertOk()
        ->assertSee('Approval Needed');

    $this->post(route('contacts.approve', $contact))->assertRedirect();

    $contact->refresh();

    expect($contact->approval_status)->toBe('approved')
        ->and($contact->approved_by)->toBe($this->user->id)
        ->and($contact->approved_at)->not->toBeNull();

    // Drain the "approved" toast flashed by the approve redirect above — it
    // otherwise bleeds into the very next request and contains the contact's
    // name, which would falsely trip the assertDontSee below.
    $this->get(route('contacts.pending'));

    $this->actingAs($manager)
        ->get(route('contacts.index'))
        ->assertOk()
        ->assertDontSee('Approval Needed');
});

it('shows added and approved user names on the contact detail page', function () {
    // The contacts list table doesn't carry these columns (dropped in favor of
    // City/Added date) — this info lives on the individual contact page instead.
    $manager = makeManagerOnTeam($this->user->current_team_id);
    $manager->forceFill(['name' => 'Manager Added User'])->save();
    $this->user->forceFill(['name' => 'Admin Approved User'])->save();

    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'owner_id' => $manager->id,
        'name' => 'Visible Approved Contact',
        'approval_status' => 'approved',
        'approved_by' => $this->user->id,
        'approved_at' => now(),
    ]);

    $this->get(route('contacts.show', $contact))
        ->assertOk()
        ->assertSee('Added by')
        ->assertSee('Approved by')
        ->assertSee('Manager Added User')
        ->assertSee('Admin Approved User');
});

it('lets super admin approve a manager-created contact', function () {
    $superAdmin = makeUser(['role' => \App\Support\Roles::SUPER_ADMIN]);
    $manager = makeManagerOnTeam($this->user->current_team_id);
    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'owner_id' => $manager->id,
        'name' => 'Super Approval Needed',
        'approval_status' => 'pending',
        'approved_by' => null,
        'approved_at' => null,
    ]);

    $this->actingAs($superAdmin)
        ->post(route('contacts.approve', $contact))
        ->assertRedirect();

    $contact->refresh();

    expect($contact->approval_status)->toBe('approved')
        ->and($contact->approved_by)->toBe($superAdmin->id)
        ->and($contact->approved_at)->not->toBeNull();
});

it('blocks manager from approving manager-created contacts', function () {
    $manager = makeManagerOnTeam($this->user->current_team_id);
    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'owner_id' => $manager->id,
        'approval_status' => 'pending',
        'approved_by' => null,
        'approved_at' => null,
    ]);

    $this->actingAs($manager);

    $this->get(route('contacts.pending'))->assertForbidden();
    $this->post(route('contacts.approve', $contact))->assertForbidden();

    expect($contact->fresh()->approval_status)->toBe('pending');
});

it('shows a contact', function () {
    $c = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $this->get(route('contacts.show', $c))->assertOk()->assertSee($c->name);
});

it('hides contacts from another team (403 via policy)', function () {
    $other = makeUser();
    $foreign = Contact::factory()->create(['team_id' => $other->current_team_id]);
    // ContactPolicy::view() denies cross-team access, which Laravel turns into a 403.
    $this->get('/contacts/'.$foreign->id)->assertForbidden();
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

it('lets clerks search by formatted phone number', function () {
    Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'name'    => 'Formatted Contact',
        'phone'   => '+1 (555) 123-4567',
    ]);

    $clerk = makeClerkOnTeam($this->user->current_team_id);

    $this->actingAs($clerk)
        ->get('/contacts?number=5551234567')
        ->assertOk()
        ->assertSee('Formatted Contact');

    $this->actingAs($clerk)
        ->get('/contacts?number=1-555-123-4567')
        ->assertOk()
        ->assertSee('Formatted Contact');
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
        'country' => 'in',
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
        'country' => 'us',
    ])->assertRedirect();

    expect($contact->fresh()->country)->toBe('us');
});

it('lets clerks view and add notes', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $clerk = makeClerkOnTeam($this->user->current_team_id);

    $this->actingAs($clerk);

    $this->get(route('contacts.show', $contact))->assertOk()->assertSee('Add or edit your notes');

    $this->post(route('contacts.notes.store', $contact), [
        'note_html' => 'Spoke on the phone, call back tomorrow.',
    ])->assertRedirect();

    $note = \App\Models\ContactNote::where('contact_id', $contact->id)->firstOrFail();
    expect($note->user_id)->toBe($clerk->id)
        ->and($note->note_html)->toBe('Spoke on the phone, call back tomorrow.');
});

it('blocks clerks from editing contact fields via update (notes only)', function () {
    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'name' => 'Original Name',
        'phone' => '9503466923',
        'status' => 'active',
    ]);
    $clerk = makeClerkOnTeam($this->user->current_team_id);

    $this->actingAs($clerk);

    $this->get(route('contacts.edit', $contact))->assertOk()->assertSee('Save changes');

    // ContactPolicy::update() requires Manager+, so a clerk's PUT falls through to
    // the updateNotes-only branch — name/status are left untouched.
    $this->put(route('contacts.update', $contact), [
        'name' => 'Updated By Clerk',
        'phone' => '9503466923',
        'status' => 'banned',
    ])->assertRedirect(route('contacts.show', $contact));

    $contact->refresh();
    expect($contact->name)->toBe('Original Name')
        ->and($contact->status)->toBe('active');
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
