<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Message;
use App\Services\TwilioClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class CallsController extends Controller
{
    public function index(): View
    {
        Gate::authorize('manage-calls');

        $calls = Message::query()
            ->where('channel', 'voice')
            ->with('contact')
            ->latest()
            ->paginate(20);

        return view('calls.index', compact('calls'));
    }

    /**
     * Log a placeholder call entry. In production this is fired by the
     * Twilio JS SDK after the connection succeeds, with a real CallSid.
     */
    public function log(Request $request, TwilioClient $twilio): RedirectResponse
    {
        $data = $request->validate([
            'contact_id' => ['required', 'integer'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'max:32'],
        ]);

        $contact = Contact::findOrFail($data['contact_id']);
        Gate::authorize('manage-calls');

        Message::create([
            'team_id' => $contact->team_id,
            'contact_id' => $contact->id,
            'user_id' => $request->user()->id,
            'channel' => 'voice',
            'direction' => 'outbound',
            'status' => $data['status'] ?? 'completed',
            'from_number' => $twilio->from() ?? 'system',
            'to_number' => $contact->phone ?? 'unknown',
            'body' => $twilio->isFake() ? 'Test call (fake mode)' : null,
            'twilio_sid' => $twilio->isFake() ? 'FAKE'.strtoupper(\Illuminate\Support\Str::random(32)) : null,
            'twilio_payload' => ['duration' => $data['duration_seconds'] ?? 0],
            'sent_at' => now(),
        ]);

        $contact->forceFill(['last_contacted_at' => now()])->save();

        return back()->with('toast', [
            'type' => 'success',
            'message' => $twilio->isFake() ? 'Call recorded (fake mode).' : 'Call logged.',
        ]);
    }

    /**
     * Issue a Twilio Voice access token for the in-browser SDK.
     *
     * Returns 503 in fake mode (no creds), otherwise a JWT.
     */
    public function token(Request $request): JsonResponse
    {
        if (config('twilio.fake')) {
            return response()->json([
                'error' => 'Twilio is not configured. Add credentials in Settings or .env to enable browser calling.',
            ], 503);
        }

        $identity = 'user_'.$request->user()->id;

        $token = new AccessToken(
            config('twilio.sid'),
            config('twilio.api_key'),
            config('twilio.api_secret'),
            3600,
            $identity,
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid(config('twilio.application_sid'));
        $voiceGrant->setIncomingAllow(true);
        $token->addGrant($voiceGrant);

        return response()->json([
            'identity' => $identity,
            'token' => $token->toJWT(),
        ]);
    }
}
