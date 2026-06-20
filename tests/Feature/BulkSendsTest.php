<?php

use App\Jobs\SendBulkMessageJob;
use App\Models\BulkSend;
use App\Models\Contact;
use App\Models\Message;
use App\Services\TwilioClient;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->user = makeUser();
    $this->actingAs($this->user);
});

it('redirects unauthenticated visitors to login', function () {
    auth()->logout();
    $this->get('/bulk-sends')->assertRedirect('/login');
    $this->get('/bulk-sends/compose?contact_ids=1')->assertRedirect('/login');
});

it('renders the composer with the selected contacts', function () {
    $contacts = Contact::factory()->count(3)->create(['team_id' => $this->user->current_team_id]);

    $this->get('/bulk-sends/compose?contact_ids='.$contacts->pluck('id')->join(','))
        ->assertOk()
        ->assertSee('Send to selection')
        ->assertSee('3 selected');
});

it('refuses to compose when no contacts are selected', function () {
    $this->get('/bulk-sends/compose?contact_ids=')->assertStatus(422);
});

it('creates a BulkSend row and dispatches one job per eligible recipient', function () {
    Bus::fake();
    $contacts = Contact::factory()->count(4)->create([
        'team_id' => $this->user->current_team_id,
        'email' => fn () => fake()->unique()->safeEmail(),
    ]);

    $res = $this->post('/bulk-sends', [
        'contact_ids' => $contacts->pluck('id')->all(),
        'channel' => 'email',
        'subject' => 'Hello {{first_name}}',
        'body' => 'Hi {{first_name}}, thanks for being a customer.',
    ]);

    $bulk = BulkSend::first();
    expect($bulk)->not->toBeNull();
    expect($bulk->total_count)->toBe(4);
    expect($bulk->channel)->toBe('email');
    expect($bulk->subject)->toBe('Hello {{first_name}}');
    Bus::assertDispatchedTimes(SendBulkMessageJob::class, 4);

    $res->assertRedirect(route('bulk-sends.show', $bulk));
});

it('filters out contacts without the channel address', function () {
    Bus::fake();
    $withEmail = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'has@example.com']);
    $noEmail   = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => null]);

    $this->post('/bulk-sends', [
        'contact_ids' => [$withEmail->id, $noEmail->id],
        'channel' => 'email',
        'subject' => 'Hi',
        'body' => 'Hello.',
    ]);

    $bulk = BulkSend::first();
    expect($bulk->total_count)->toBe(1);
    Bus::assertDispatchedTimes(SendBulkMessageJob::class, 1);
});

it('rejects the send when no recipient has the right address', function () {
    Bus::fake();
    $contacts = Contact::factory()->count(2)->create([
        'team_id' => $this->user->current_team_id,
        'phone' => null,
    ]);

    $this->post('/bulk-sends', [
        'contact_ids' => $contacts->pluck('id')->all(),
        'channel' => 'sms',
        'body' => 'hi',
    ])->assertSessionHasErrors('contact_ids');

    expect(BulkSend::count())->toBe(0);
    Bus::assertNotDispatched(SendBulkMessageJob::class);
});

it('renders merge tokens per recipient when the job runs', function () {
    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'name' => 'Ada Lovelace',
        'company' => 'Analytical Engines',
        'phone' => '+15551234567',
    ]);

    $bulk = BulkSend::create([
        'team_id' => $this->user->current_team_id,
        'user_id' => $this->user->id,
        'channel' => 'sms',
        'body' => 'Hi {{first_name}}, regards from {{company}}.',
        'contact_ids' => [$contact->id],
        'total_count' => 1,
        'started_at' => now(),
    ]);

    (new SendBulkMessageJob($bulk, $contact))->handle(app(TwilioClient::class));

    $sent = Message::where('contact_id', $contact->id)->first();
    expect($sent)->not->toBeNull();
    expect($sent->body)->toBe('Hi Ada, regards from Analytical Engines.');

    $bulk->refresh();
    expect($bulk->sent_count)->toBe(1);
    expect($bulk->failed_count)->toBe(0);
    expect($bulk->finished_at)->not->toBeNull();
});

it('increments failed_count when the contact lacks the address', function () {
    $contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'phone' => null,
    ]);

    $bulk = BulkSend::create([
        'team_id' => $this->user->current_team_id,
        'user_id' => $this->user->id,
        'channel' => 'sms',
        'body' => 'Hi.',
        'contact_ids' => [$contact->id],
        'total_count' => 1,
        'started_at' => now(),
    ]);

    (new SendBulkMessageJob($bulk, $contact))->handle(app(TwilioClient::class));

    $bulk->refresh();
    expect($bulk->sent_count)->toBe(0);
    expect($bulk->failed_count)->toBe(1);
});

it('shows progress on the detail page', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'foo@bar.test']);

    $bulk = BulkSend::create([
        'team_id' => $this->user->current_team_id,
        'user_id' => $this->user->id,
        'channel' => 'email',
        'subject' => 'Hello',
        'body' => 'Body.',
        'contact_ids' => [$contact->id],
        'total_count' => 1,
        'sent_count' => 1,
        'started_at' => now()->subMinute(),
        'finished_at' => now(),
    ]);

    $this->get('/bulk-sends/'.$bulk->id)
        ->assertOk()
        ->assertSee('completed')
        ->assertSee('Hello');
});

it('saves a reusable template when save_template is ticked', function () {
    Bus::fake();
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'hi@example.com']);

    $this->post('/bulk-sends', [
        'contact_ids' => [$contact->id],
        'channel' => 'email',
        'subject' => 'Hello {{first_name}}',
        'body' => 'Body.',
        'save_template' => '1',
        'template_name' => 'Cold outreach intro',
    ]);

    $tpl = \App\Models\BulkSendTemplate::first();
    expect($tpl)->not->toBeNull();
    expect($tpl->name)->toBe('Cold outreach intro');
    expect($tpl->channel)->toBe('email');
    expect($tpl->team_id)->toBe($this->user->current_team_id);
});

it('does not save a template when save_template is unchecked', function () {
    Bus::fake();
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id, 'email' => 'hi@example.com']);

    $this->post('/bulk-sends', [
        'contact_ids' => [$contact->id],
        'channel' => 'email',
        'subject' => 'Hi',
        'body' => 'Body.',
    ]);

    expect(\App\Models\BulkSendTemplate::count())->toBe(0);
});

it('prefills the composer when a template_id is passed', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);
    $tpl = \App\Models\BulkSendTemplate::create([
        'user_id' => $this->user->id,
        'name' => 'Renewal nudge',
        'channel' => 'sms',
        'body' => 'Hi {{first_name}}, time to renew.',
    ]);

    $this->get("/bulk-sends/compose?contact_ids={$contact->id}&template_id={$tpl->id}")
        ->assertOk()
        ->assertSee('time to renew');
});

it('deletes a template via the templates.destroy endpoint', function () {
    $tpl = \App\Models\BulkSendTemplate::create([
        'user_id' => $this->user->id,
        'name' => 'Doomed',
        'channel' => 'email',
        'subject' => 'Hi',
        'body' => 'Body.',
    ]);

    $this->delete("/bulk-sends/templates/{$tpl->id}")->assertRedirect();
    expect(\App\Models\BulkSendTemplate::find($tpl->id))->toBeNull();
});

it('blocks deleting another team\'s template', function () {
    $otherUser = makeUser();
    $tpl = \App\Models\BulkSendTemplate::create([
        'team_id' => $otherUser->current_team_id,
        'user_id' => $otherUser->id,
        'name' => 'Foreign',
        'channel' => 'email',
        'body' => 'x',
    ]);

    // BelongsToTeam global scope hides it; route-model binding 404s.
    $this->delete("/bulk-sends/templates/{$tpl->id}")->assertStatus(404);
});

it('isolates bulk sends by team on the index page', function () {
    $mine    = BulkSend::create(['team_id' => $this->user->current_team_id, 'user_id' => $this->user->id, 'channel' => 'sms', 'body' => 'mine', 'contact_ids' => [], 'total_count' => 0]);
    $others  = BulkSend::create(['team_id' => makeUser()->current_team_id, 'user_id' => 1, 'channel' => 'sms', 'body' => 'theirs', 'contact_ids' => [], 'total_count' => 0]);

    $res = $this->get('/bulk-sends')->assertOk();

    expect($res->getContent())->toContain('mine');
    expect($res->getContent())->not->toContain('theirs');
});
