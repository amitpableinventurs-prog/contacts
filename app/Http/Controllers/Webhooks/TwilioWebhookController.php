<?php

namespace App\Http\Controllers\Webhooks;

use App\Events\MessageReceived;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TwilioWebhookController extends Controller
{
    /**
     * Receive an inbound SMS from Twilio.
     *
     * Twilio posts: MessageSid, From, To, Body (and many others).
     */
    public function incomingSms(Request $request): Response
    {
        $rawFrom = (string) $request->input('From', '');
        $isWhatsApp = str_starts_with($rawFrom, 'whatsapp:');
        $from = $isWhatsApp ? substr($rawFrom, strlen('whatsapp:')) : $rawFrom;
        $body = (string) $request->input('Body', '');
        $sid = $request->input('MessageSid');
        $channel = $isWhatsApp ? 'whatsapp' : 'sms';

        Log::info('Twilio inbound message', compact('from', 'body', 'sid', 'channel'));

        $contact = Contact::withoutTeamScope()
            ->where('phone', $from)
            ->orWhere('phone', preg_replace('/\D/', '', $from))
            ->first();

        if (! $contact) {
            return response('', 204);
        }

        $message = null;
        Message::withoutEvents(function () use ($contact, $request, $sid, $from, $body, $channel, &$message) {
            $message = Message::create([
                'team_id' => $contact->team_id,
                'contact_id' => $contact->id,
                'user_id' => null,
                'channel' => $channel,
                'direction' => 'inbound',
                'status' => 'received',
                'from_number' => $from,
                'to_number' => (string) $request->input('To', ''),
                'body' => $body,
                'twilio_sid' => $sid,
                'twilio_payload' => $request->all(),
                'sent_at' => now(),
            ]);
        });

        $contact->forceFill(['last_contacted_at' => now()])->save();

        if ($message) {
            broadcast(new MessageReceived($message));
        }

        return response('<Response/>', 200, ['Content-Type' => 'text/xml']);
    }
}
