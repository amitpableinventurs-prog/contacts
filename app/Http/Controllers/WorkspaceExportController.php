<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WorkspaceExportController extends Controller
{
    public function download(Request $request): StreamedResponse
    {
        Gate::authorize('manage-export');

        $team = $request->user()->currentTeam;
        abort_unless($team, 404);

        $stem = preg_replace('/[^A-Za-z0-9-]/', '-', strtolower($team->name));
        $filename = "contacts-{$stem}-" . now()->format('Ymd') . ".csv";

        // Groups are few per team — resolve names from a map instead of
        // eager-loading a relation on every contact row.
        $groupNames = Group::where('team_id', $team->id)->pluck('name', 'id');

        return response()->streamDownload(function () use ($team, $groupNames) {
            set_time_limit(0);

            $fp = fopen('php://output', 'w');

            fputcsv($fp, ['Name', 'Email', 'Phone', 'Company', 'Job Title', 'Website', 'Address', 'Notes', 'Group', 'Lifecycle Stage', 'Created At']);

            // Stream in id-ordered chunks so lakhs of contacts never sit in
            // memory at once and the download starts immediately.
            $query = Contact::where('team_id', $team->id)
                ->where(function ($q) {
                    $q->whereNull('approval_status')->orWhere('approval_status', '!=', 'pending');
                })
                ->select(['id', 'name', 'email', 'phone', 'company', 'job_title', 'website', 'address', 'notes', 'group_id', 'lifecycle_stage', 'created_at']);

            $rows = 0;
            foreach ($query->lazyById(1000) as $c) {
                fputcsv($fp, [
                    $c->name,
                    $c->email ?? '',
                    $c->phone ?? '',
                    $c->company ?? '',
                    $c->job_title ?? '',
                    $c->website ?? '',
                    $c->address ?? '',
                    $c->notes ?? '',
                    $c->group_id ? ($groupNames[$c->group_id] ?? '') : '',
                    $c->lifecycle_stage ?? '',
                    $c->created_at?->format('Y-m-d'),
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
