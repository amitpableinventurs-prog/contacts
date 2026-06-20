<?php

use App\Models\Contact;
use App\Models\Message;

beforeEach(function () {
    $this->user = makeUser();
    $this->actingAs($this->user);
    $this->contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'phone' => '+15554443333',
    ]);
});

it('sends an SMS in fake mode', function () {
    $this->post(route('sms.store', $this->contact), [
        'body' => 'Hello world',
    ])->assertRedirect();

    $msg = Message::latest()->first();
    expect($msg)
        ->channel->toBe('sms')
        ->direction->toBe('outbound')
        ->body->toBe('Hello world')
        ->and($msg->twilio_sid)->toStartWith('FAKE');
});

it('sends a WhatsApp message with the channel flag', function () {
    $this->post(route('sms.store', $this->contact), [
        'body' => 'WhatsApp test',
        'channel' => 'whatsapp',
    ])->assertRedirect();

    expect(Message::latest()->first()->channel)->toBe('whatsapp');
});

it('refuses to send without a phone number', function () {
    $noPhone = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'phone' => null,
    ]);
    $this->post(route('sms.store', $noPhone), ['body' => 'hi'])
        ->assertSessionHasErrors('body');
});

it('spell-checks text via fake mode', function () {
    $res = $this->postJson(route('messaging.spell-check'), [
        'text' => 'teh meeting is tommorow at 2pm. definately.',
    ]);
    $res->assertOk();
    expect($res->json('fake'))->toBeTrue();
    expect($res->json('text'))->toContain('The meeting')
        ->and($res->json('text'))->toContain('tomorrow')
        ->and($res->json('text'))->toContain('Definitely');
});

it('translates text via fake mode (prefixed)', function () {
    $res = $this->postJson(route('messaging.translate'), [
        'text' => 'Hello there',
        'language' => 'Spanish',
    ]);
    $res->assertOk();
    expect($res->json('text'))->toBe('[SPANISH] Hello there');
});
