<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WorkspaceExportController extends Controller
{
    public function show(Request $request)
    {
        Gate::authorize('manage-export');
        return view('workspace.export');
    }

    public function verifyPin(Request $request): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('manage-export');

        $request->validate(['pin' => 'required|string']);

        $correct = $request->input('pin') === env('EXPORT_PIN');
        session(['export_pin_verified' => $correct]);

        return response()->json(['verified' => $correct]);
    }

    public function download(Request $request): StreamedResponse
    {
        Gate::authorize('manage-export');

        abort_unless(session('export_pin_verified'), 403, 'PIN verification required.');

        $team = $request->user()->currentTeam;
        abort_unless($team, 404);

        $stem = preg_replace('/[^A-Za-z0-9-]/', '-', strtolower($team->name));
        $filename = "contacts-{$stem}-" . now()->format('Ymd') . ".csv";

        $groupNames = Group::where('team_id', $team->id)->pluck('name', 'id');

        return response()->streamDownload(function () use ($team, $groupNames) {
            set_time_limit(0);

            $fp = fopen('php://output', 'w');

            fputcsv($fp, ['Name', 'Email', 'Phone', 'City', 'Birthday', 'Company', 'Job Title', 'Website', 'Address', 'Status', 'Rating', 'Lifecycle Stage', 'Notes', 'Comment', 'Facebook', 'Twitter', 'LinkedIn', 'Group', 'Created At', 'Last Contacted']);

            $query = Contact::where('team_id', $team->id)
                ->where(function ($q) {
                    $q->whereNull('approval_status')->orWhere('approval_status', '!=', 'pending');
                })
                ->select(['id', 'name', 'email', 'phone', 'city', 'birthday', 'company', 'job_title', 'website', 'address', 'status', 'rating', 'notes', 'admin_comment', 'facebook', 'twitter', 'linkedin', 'lifecycle_stage', 'group_id', 'created_at', 'last_contacted_at']);

            $rows = 0;
            foreach ($query->lazyById(1000) as $c) {
                fputcsv($fp, [
                    $c->name,
                    $c->email ?? '',
                    $c->phone ?? '',
                    $c->city ?? '',
                    $c->birthday?->format('Y-m-d') ?? '',
                    $c->company ?? '',
                    $c->job_title ?? '',
                    $c->website ?? '',
                    $c->address ?? '',
                    $c->status ?? 'active',
                    $c->rating ?? '',
                    $c->lifecycle_stage ?? '',
                    $c->notes ?? '',
                    $c->admin_comment ?? '',
                    $c->facebook ?? '',
                    $c->twitter ?? '',
                    $c->linkedin ?? '',
                    $c->group_id ? ($groupNames[$c->group_id] ?? '') : '',
                    $c->created_at?->format('Y-m-d'),
                    $c->last_contacted_at?->format('Y-m-d'),
                ]);

                if (++$rows % 1000 === 0) {
                    flush();
                }
            }

            fclose($fp);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
