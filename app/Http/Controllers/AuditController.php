<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-audit');

        $teamId = Auth::user()->current_team_id;
        $contactIds = Contact::query()->pluck('id');

        $activities = Activity::query()
            ->where('subject_type', Contact::class)
            ->whereIn('subject_id', $contactIds)
            ->with(['causer', 'subject'])
            ->latest()
            ->paginate(40);

        return view('audit.index', compact('activities'));
    }

    public function forContact(Contact $contact): View
    {
        Gate::authorize('view', $contact);

        $activities = $contact->activities()
            ->with('causer')
            ->latest()
            ->paginate(30);

        return view('audit.contact', compact('contact', 'activities'));
    }
}
