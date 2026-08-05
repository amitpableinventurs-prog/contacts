<?php

use App\Models\Contact;
use App\Models\Group;
use App\Models\Reminder;
use App\Models\Tag;

beforeEach(function () {
    $this->user = makeUser();
    $this->actingAs($this->user);
});

it('redirects unauthenticated users', function () {
    auth()->logout();
    $this->get('/workspace/export')->assertRedirect('/login');
});

it('exports contact category and tags in the contacts csv', function () {
    $this->user = makeUser(['role' => \App\Support\Roles::SUPER_ADMIN]);
    $this->actingAs($this->user);

    $group = Group::create(['team_id' => $this->user->current_team_id, 'name' => 'VIP Customers']);
    $tag = Tag::create(['team_id' => $this->user->current_team_id, 'name' => 'Founder', 'slug' => 'founder']);
    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'group_id' => $group->id,
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.test',
    ]);
    $contact->tags()->attach($tag->id);

    $res = $this
        ->withSession(['export_pin_verified' => true])
        ->get(route('workspace.export-download'));

    $res->assertOk();
    expect($res->headers->get('content-type'))->toContain('text/csv');

    ob_start();
    $res->sendContent();
    $csv = ob_get_clean();

    expect($csv)->toContain('Category')
        ->toContain('Tags')
        ->toContain('VIP Customers')
        ->toContain('Founder');
});
