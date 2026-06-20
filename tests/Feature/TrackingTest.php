<?php

use App\Models\Contact;
use App\Models\EmailMessage;
use Illuminate\Support\Str;

it('returns a 1x1 GIF and increments the open count', function () {
    $user = makeUser();
    $contact = Contact::factory()->create(['team_id' => $user->current_team_id]);

    $email = EmailMessage::create([
        'team_id' => $user->current_team_id,
        'contact_id' => $contact->id,
        'user_id' => $user->id,
        'from_email' => 'me@example.com',
        'to_email' => 'them@example.com',
        'subject' => 'Hi',
        'body_text' => 'hello',
        'tracking_id' => (string) Str::uuid(),
    ]);

    $res = $this->get('/track/email/'.$email->tracking_id.'.gif');
    $res->assertOk();
    expect($res->headers->get('Content-Type'))->toBe('image/gif');

    expect($email->fresh()->opens_count)->toBe(1);
    expect($email->fresh()->first_opened_at)->not->toBeNull();
});

it('ignores unknown tracking ids gracefully', function () {
    $this->get('/track/email/00000000-0000-0000-0000-000000000000.gif')->assertOk();
});
