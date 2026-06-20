<?php

namespace App\Http\Controllers;

use App\Jobs\SendBulkMessageJob;
use App\Models\BulkSend;
use App\Models\BulkSendTemplate;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BulkSendsController extends Controller
{
    public function compose(Request $request): View
    {
        Gate::authorize('manage-bulk');

        $ids = collect(explode(',', (string) $request->query('contact_ids', '')))
            ->filter()
            ->map(fn ($v) => (int) $v)
            ->values();

        $contacts = Contact::whereIn('id', $ids)->get();
        abort_if($contacts->isEmpty(), 422, 'No contacts selected.');

        $templates = BulkSendTemplate::orderBy('name')->get();
        $template = $request->filled('template_id')
            ? BulkSendTemplate::find($request->integer('template_id'))
            : null;

        return view('bulk-sends.compose', compact('contacts', 'templates', 'template'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-bulk');

        $data = $request->validate([
            'contact_ids' => ['required', 'array', 'min:1'],
            'contact_ids.*' => ['integer'],
            'channel' => ['required', 'in:sms,whatsapp,email'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            'save_template' => ['nullable', 'boolean'],
            'template_name' => ['nullable', 'string', 'max:120', 'required_with:save_template'],
        ]);

        // Optional: save this composition as a reusable template.
        if ($request->boolean('save_template') && ! empty($data['template_name'])) {
            BulkSendTemplate::create([
                'user_id' => $request->user()->id,
                'name' => $data['template_name'],
                'channel' => $data['channel'],
                'subject' => $data['channel'] === 'email' ? $data['subject'] : null,
                'body' => $data['body'],
            ]);
        }

        $contacts = Contact::whereIn('id', $data['contact_ids'])->get();

        // Filter to contacts that actually have the address for this channel.
        $eligible = $contacts->filter(function (Contact $c) use ($data) {
            return $data['channel'] === 'email' ? $c->email : $c->phone;
        });

        if ($eligible->isEmpty()) {
            $missing = $data['channel'] === 'email' ? 'an email address' : 'a phone number';
            return back()->withErrors(['contact_ids' => "None of the selected contacts have {$missing}."])->withInput();
        }

        $bulkSend = BulkSend::create([
            'user_id' => $request->user()->id,
            'channel' => $data['channel'],
            'subject' => $data['channel'] === 'email' ? $data['subject'] : null,
            'body' => $data['body'],
            'contact_ids' => $eligible->pluck('id')->all(),
            'total_count' => $eligible->count(),
            'started_at' => now(),
        ]);

        foreach ($eligible as $contact) {
            SendBulkMessageJob::dispatch($bulkSend, $contact);
        }

        $skipped = $contacts->count() - $eligible->count();
        $msg = "Queued {$eligible->count()} message" . ($eligible->count() === 1 ? '' : 's') . '.';
        if ($skipped > 0) {
            $msg .= " {$skipped} skipped (missing " . ($data['channel'] === 'email' ? 'email' : 'phone') . ').';
        }

        return redirect()
            ->route('bulk-sends.show', $bulkSend)
            ->with('toast', ['type' => 'success', 'message' => $msg]);
    }

    public function index(): View
    {
        Gate::authorize('manage-bulk');

        $sends = BulkSend::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('bulk-sends.index', compact('sends'));
    }

    public function show(BulkSend $bulkSend): View
    {
        Gate::authorize('manage-bulk');
        abort_unless($bulkSend->team_id === auth()->user()->current_team_id, 404);

        // Refresh counters from DB (jobs may have incremented since model was loaded).
        $bulkSend->refresh();

        $contacts = Contact::whereIn('id', $bulkSend->contact_ids ?? [])->get();

        return view('bulk-sends.show', compact('bulkSend', 'contacts'));
    }

    public function destroyTemplate(BulkSendTemplate $template): RedirectResponse
    {
        Gate::authorize('manage-bulk');
        abort_unless($template->team_id === auth()->user()->current_team_id, 404);

        $template->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Template deleted.']);
    }
}
