<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Reminder;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RemindersController extends Controller
{
    public function index(): View
    {
        Gate::authorize('manage-reminders');
        $reminders = Reminder::with('contact')
            ->where('status', 'pending')
            ->orderBy('remind_at')
            ->get();

        $now = now();
        $tomorrow = $now->copy()->endOfDay();
        $endOfWeek = $now->copy()->endOfWeek();

        $grouped = [
            'overdue' => $reminders->filter(fn ($r) => $r->remind_at->lt($now))->values(),
            'today' => $reminders->filter(fn ($r) => $r->remind_at->gte($now) && $r->remind_at->lte($tomorrow))->values(),
            'this_week' => $reminders->filter(fn ($r) => $r->remind_at->gt($tomorrow) && $r->remind_at->lte($endOfWeek))->values(),
            'later' => $reminders->filter(fn ($r) => $r->remind_at->gt($endOfWeek))->values(),
        ];

        $completed = Reminder::where('status', 'completed')
            ->with('contact')
            ->latest('completed_at')
            ->limit(10)
            ->get();

        return view('reminders.index', compact('grouped', 'completed'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-reminders');

        $data = $request->validate([
            'contact_id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'remind_at' => ['required', 'date', 'after_or_equal:now'],
            'notify_email' => ['nullable', 'boolean'],
            'notify_sms' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] = Auth::id();
        $data['notify_email'] = (bool) ($data['notify_email'] ?? true);
        $data['notify_sms'] = (bool) ($data['notify_sms'] ?? false);

        if (! empty($data['contact_id'])) {
            $contact = Contact::find($data['contact_id']);
            if (! $contact) {
                return back()->withErrors(['contact_id' => 'Contact not found.']);
            }
        } else {
            $data['contact_id'] = null;
        }

        Reminder::create($data);

        return back()->with('toast', ['type' => 'success', 'message' => 'Reminder scheduled.']);
    }

    public function complete(Reminder $reminder): RedirectResponse
    {
        abort_unless(Auth::user()->isSuperAdmin() || $reminder->team_id === Auth::user()->current_team_id, 403);

        $reminder->forceFill([
            'status' => 'completed',
            'completed_at' => now(),
        ])->save();

        return back()->with('toast', ['type' => 'success', 'message' => 'Marked as done.']);
    }

    public function destroy(Reminder $reminder): RedirectResponse
    {
        abort_unless(Auth::user()->isSuperAdmin() || $reminder->team_id === Auth::user()->current_team_id, 403);
        $reminder->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Reminder deleted.']);
    }

    public function calendar(Request $request): View
    {
        Gate::authorize('manage-reminders');
        // Parse ?month=YYYY-MM, default to current month
        $monthInput = $request->string('month')->toString();
        try {
            $month = $monthInput
                ? CarbonImmutable::createFromFormat('Y-m', $monthInput)->startOfMonth()
                : CarbonImmutable::now()->startOfMonth();
        } catch (\Throwable) {
            $month = CarbonImmutable::now()->startOfMonth();
        }

        // Grid spans complete weeks containing this month (Sun → Sat or locale-aware)
        $gridStart = $month->startOfWeek(\Carbon\Carbon::SUNDAY);
        $gridEnd = $month->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);

        $reminders = Reminder::with('contact')
            ->whereBetween('remind_at', [$gridStart, $gridEnd])
            ->orderBy('remind_at')
            ->get()
            ->groupBy(fn ($r) => $r->remind_at->format('Y-m-d'));

        // Build the grid of date strings.
        $days = collect();
        for ($d = $gridStart; $d->lte($gridEnd); $d = $d->addDay()) {
            $days->push($d);
        }

        return view('reminders.calendar', [
            'month' => $month,
            'days' => $days,
            'reminders' => $reminders,
            'today' => CarbonImmutable::today(),
            'prevMonth' => $month->subMonth()->format('Y-m'),
            'nextMonth' => $month->addMonth()->format('Y-m'),
        ]);
    }
}
