<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Message;
use App\Services\AnthropicClient;
use App\Services\TwilioClient;
use App\Support\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SmsController extends Controller
{
    public function index(): View
    {
        Gate::authorize('messaging');

        $conversations = Contact::query()
            ->whereHas('messages')
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->withCount('messages')
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('contact_id', 'contacts.id')
                    ->latest()
                    ->limit(1)
            )
            ->paginate(20);

        return view('sms.index', compact('conversations'));
    }

    public function sent(): View
    {
        Gate::authorize('messaging');

        $teamId = Auth::user()->current_team_id;

        $messages = Message::with('contact', 'user')
            ->where('direction', 'outbound')
            ->when(! Auth::user()->isSuperAdmin(), fn ($q) => $q->where('team_id', $teamId))
            ->latest('sent_at')
            ->paginate(20);

        return view('sms.sent', compact('messages'));
    }

    public function destroy(Message $message): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN), 403);
        if ($message->contact) {
            Gate::authorize('view', $message->contact);
        } else {
            abort_unless(Auth::user()->isSuperAdmin(), 403);
        }

        $message->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Message deleted.']);
    }

    public function show(Contact $contact): View
    {
        Gate::authorize('view', $contact);

        $messages = $contact->messages()
            ->oldest()
            ->paginate(50);

        return view('sms.show', compact('contact', 'messages'));
    }

    public function store(Request $request, Contact $contact, TwilioClient $twilio): RedirectResponse
    {
        Gate::authorize('view', $contact);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:1600'],
            'channel' => ['nullable', 'in:sms,whatsapp'],
        ]);

        if (! $contact->phone) {
            return back()->withErrors(['body' => 'This contact has no phone number on file.']);
        }

        $channel = $data['channel'] ?? 'sms';
        $result = $twilio->sendSms($contact->phone, $data['body'], $channel);

        Message::create([
            'team_id' => $contact->team_id,
            'contact_id' => $contact->id,
            'user_id' => $request->user()->id,
            'channel' => $channel,
            'direction' => 'outbound',
            'status' => $result['status'],
            'from_number' => $twilio->from() ?? 'fake-system',
            'to_number' => $contact->phone,
            'body' => $data['body'],
            'twilio_sid' => $result['sid'],
            'twilio_payload' => $result['payload'],
            'sent_at' => now(),
        ]);

        // If Twilio rejected the message, surface the human-readable reason in
        // the validation errors so the composer shows it inline instead of
        // looking like the send succeeded.
        if ($result['status'] === 'failed' && ! empty($result['error'])) {
            return back()->withErrors(['body' => $result['error']])->withInput();
        }

        $contact->forceFill(['last_contacted_at' => now()])->save();

        return back()->with('toast', [
            'type' => 'success',
            'message' => $twilio->isFake()
                ? ucfirst($channel).' recorded (fake mode).'
                : ucfirst($channel).' sent.',
        ]);
    }

    public function spellCheck(Request $request, AnthropicClient $ai): JsonResponse
    {
        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
        ]);

        return response()->json([
            'fake' => $ai->isFake(),
            'text' => $ai->spellCheck($data['text']),
        ]);
    }

    public function translate(Request $request, AnthropicClient $ai): JsonResponse
    {
        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
            'language' => ['nullable', 'string', 'max:60'],
        ]);

        return response()->json([
            'fake' => $ai->isFake(),
            'text' => $ai->translate($data['text'], $data['language'] ?? 'English'),
        ]);
    }
}
