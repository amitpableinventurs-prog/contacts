<?php

use App\Models\Contact;
use App\Models\Message;
use App\Settings\TwilioSettings;

beforeEach(function () {
    $this->user = makeUser();
    $this->contact = Contact::factory()->create([
        'team_id' => $this->user->current_team_id,
        'phone' => '+15551112222',
    ]);
});

it('records an inbound SMS in dev mode (no token, no signature)', function () {
    $this->post('/webhooks/twilio/sms', [
        'From' => '+15551112222',
        'To' => '+19990000000',
        'Body' => 'Hi from the field',
        'MessageSid' => 'SMtest123',
    ])->assertOk();

    $msg = Message::where('direction', 'inbound')->latest()->first();
    expect($msg)
        ->channel->toBe('sms')
        ->body->toBe('Hi from the field')
        ->contact_id->toBe($this->contact->id);
});

it('detects WhatsApp inbound by prefix', function () {
    $this->post('/webhooks/twilio/sms', [
        'From' => 'whatsapp:+15551112222',
        'To' => 'whatsapp:+19990000000',
        'Body' => 'wa reply',
        'MessageSid' => 'WAtest',
    ])->assertOk();

    expect(Message::latest()->first()->channel)->toBe('whatsapp');
});

it('rejects inbound when signature is required and missing', function () {
    $settings = app(TwilioSettings::class);
    $settings->token = 'TESTTOKEN';
    $settings->save();

    $this->post('/webhooks/twilio/sms', [
        'From' => '+15551112222',
        'To' => '+19990000000',
        'Body' => 'no sig',
    ])->assertForbidden();
});

it('accepts inbound with a valid X-Twilio-Signature', function () {
    $settings = app(TwilioSettings::class);
    $settings->token = 'TESTTOKEN';
    $settings->save();

    $params = ['Body' => 'signed', 'From' => '+15551112222', 'To' => '+19990000000'];
    ksort($params);
    $url = url('/webhooks/twilio/sms');
    $data = $url;
    foreach ($params as $k => $v) $data .= $k.$v;
    $sig = base64_encode(hash_hmac('sha1', $data, 'TESTTOKEN', true));

    $this->withHeader('X-Twilio-Signature', $sig)
        ->post('/webhooks/twilio/sms', $params)
        ->assertOk();
});
