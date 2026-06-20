<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Contact;
use App\Models\EmailMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmailsController extends Controller
{
    public function index(): View
    {
        Gate::authorize('manage-emails');

        $emails = EmailMessage::with('contact', 'user')
            ->latest()
            ->paginate(20);

        return view('emails.index', compact('emails'));
    }

    public function create(Request $request): View
    {
        Gate::authorize('manage-emails');

        $contact = null;
        if ($contactId = $request->integer('contact_id')) {
            $contact = Contact::find($contactId);
        }

        return view('emails.create', compact('contact'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-emails');

        $data = $request->validate([
            'contact_id' => ['required', 'integer'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $contact = Contact::findOrFail($data['contact_id']);
        Gate::authorize('view', $contact);

        if (! $contact->email) {
            return back()->withErrors(['contact_id' => 'This contact has no email on file.'])->withInput();
        }

        $email = EmailMessage::create([
            'team_id' => $contact->team_id,
            'contact_id' => $contact->id,
            'user_id' => $request->user()->id,
            'from_email' => config('mail.from.address', 'noreply@laracontact.test'),
            'to_email' => $contact->email,
            'subject' => $data['subject'],
            'body_text' => $data['body'],
            'tracking_id' => Str::uuid()->toString(),
            'status' => 'queued',
        ]);

        Mail::to($contact->email)->queue(new ContactEmail($email));

        $email->forceFill([
            'status' => config('queue.default') === 'sync' ? 'sent' : 'queued',
            'sent_at' => config('queue.default') === 'sync' ? now() : null,
        ])->save();

        $contact->forceFill(['last_contacted_at' => now()])->save();

        return redirect()
            ->route('emails.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Email queued for delivery.',
            ]);
    }
}
