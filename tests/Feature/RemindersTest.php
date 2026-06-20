<?php

use App\Mail\ReminderDueMail;
use App\Models\Contact;
use App\Models\Reminder;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->user = makeUser(['email' => 'me@example.com']);
    $this->actingAs($this->user);
});

it('creates a reminder', function () {
    $contact = Contact::factory()->create(['team_id' => $this->user->current_team_id]);

    $this->post('/reminders', [
        'title' => 'Call them back',
        'contact_id' => $contact->id,
        'remind_at' => now()->addHour()->format('Y-m-d\TH:i'),
        'notify_email' => '1',
    ])->assertRedirect();

    $r = Reminder::first();
    expect($r)
        ->title->toBe('Call them back')
        ->contact_id->toBe($contact->id)
        ->user_id->toBe($this->user->id);
});

it('marks a reminder complete', function () {
    $r = Reminder::create([
        'team_id' => $this->user->current_team_id,
        'user_id' => $this->user->id,
        'title' => 'x',
        'remind_at' => now(),
    ]);

    $this->patch(route('reminders.complete', $r))->assertRedirect();
    expect($r->fresh())
        ->status->toBe('completed')
        ->completed_at->not->toBeNull();
});

it('the scheduled command emails users for overdue reminders', function () {
    Mail::fake();

    Reminder::create([
        'team_id' => $this->user->current_team_id,
        'user_id' => $this->user->id,
        'title' => 'Overdue thing',
        'remind_at' => now()->subMinutes(10),
        'notify_email' => true,
    ]);

    $this->artisan('reminders:send-due')->assertExitCode(0);

    Mail::assertQueued(ReminderDueMail::class);
    expect(Reminder::first()->notified_at)->not->toBeNull();
});

it('does not re-notify already-notified reminders', function () {
    Mail::fake();

    Reminder::create([
        'team_id' => $this->user->current_team_id,
        'user_id' => $this->user->id,
        'title' => 'x',
        'remind_at' => now()->subHour(),
        'notify_email' => true,
        'notified_at' => now()->subMinute(),
    ]);

    $this->artisan('reminders:send-due');
    Mail::assertNothingQueued();
});
