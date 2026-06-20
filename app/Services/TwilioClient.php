<?php

namespace App\Services;

use App\Settings\TwilioSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class TwilioClient
{
    protected ?Client $client = null;

    public function __construct(protected ?TwilioSettings $settings = null)
    {
    }

    public function isFake(): bool
    {
        // Settings UI wins if present; otherwise fall back to env-backed config.
        $configured = $this->settings?->fake_mode ?? (bool) config('twilio.fake');
        // Defensive: even if not configured-as-fake, fall back to fake when
        // no Account SID is available — prevents 500s from the real SDK.
        return $configured || ! $this->sid();
    }

    public function from(): ?string
    {
        return $this->settings?->phone_number ?: config('twilio.from');
    }

    protected function sid(): ?string
    {
        return $this->settings?->sid ?: config('twilio.sid');
    }

    protected function token(): ?string
    {
        return $this->settings?->token ?: config('twilio.token');
    }

    /**
     * Send a message and return [sid, status, payload].
     *
     * $channel: 'sms' (default) or 'whatsapp' — Twilio uses the same Messages
     * API for both; WhatsApp requires "whatsapp:" prefixed from/to numbers.
     */
    public function sendSms(string $to, string $body, string $channel = 'sms'): array
    {
        $from = $this->from();

        if ($channel === 'whatsapp') {
            $to = $this->wrapWhatsApp($to);
            $from = $from ? $this->wrapWhatsApp($from) : null;
        }

        if ($this->isFake()) {
            $sid = 'FAKE'.strtoupper(Str::random(32));
            Log::info('TwilioClient::sendSms (fake)', compact('to', 'body', 'sid', 'channel'));
            return [
                'sid' => $sid,
                'status' => 'sent',
                'payload' => ['fake' => true, 'channel' => $channel, 'to' => $to, 'body' => $body],
            ];
        }

        try {
            $message = $this->realClient()->messages->create($to, [
                'from' => $from,
                'body' => $body,
            ]);

            return [
                'sid' => $message->sid,
                'status' => $message->status,
                'payload' => $message->toArray(),
            ];
        } catch (\Twilio\Exceptions\RestException $e) {
            // Twilio refused the send — bad From number, unverified trial recipient, etc.
            // Log it, return a structured failure so callers can show a real error.
            Log::warning('TwilioClient::sendSms failed', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'to' => $to,
                'from' => $from,
                'channel' => $channel,
            ]);

            return [
                'sid' => null,
                'status' => 'failed',
                'error' => $this->humanReadableError($e),
                'payload' => ['error' => $e->getMessage(), 'code' => $e->getCode()],
            ];
        }
    }

    protected function humanReadableError(\Twilio\Exceptions\RestException $e): string
    {
        return match ($e->getCode()) {
            21212, 21214, 21217 => 'The recipient number is invalid. Use E.164 format (e.g. +15551234567).',
            21660            => 'The From number isn\'t owned by this Twilio account. In Settings → Twilio, set it to a number from "Phone Numbers → Active numbers" in your Twilio console.',
            21408            => 'Your Twilio account isn\'t enabled to send to this country.',
            21610            => 'The recipient has opted out of messages from this number.',
            21608            => 'On a Twilio trial, you can only send to verified numbers. Add the recipient at console.twilio.com → Phone Numbers → Verified Caller IDs.',
            20003            => 'Twilio authentication failed. Re-check the Account SID and Auth Token in Settings → Twilio.',
            default          => 'Twilio: '.$e->getMessage(),
        };
    }

    protected function wrapWhatsApp(string $number): string
    {
        return str_starts_with($number, 'whatsapp:') ? $number : 'whatsapp:'.$number;
    }

    protected function realClient(): Client
    {
        return $this->client ??= new Client($this->sid(), $this->token());
    }
}
