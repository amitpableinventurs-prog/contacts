<?php

namespace App\Http\Controllers;

use App\Models\Contact;
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

        $contacts = Contact::where('team_id', $team->id)->with('group')->get();

        $stem = preg_replace('/[^A-Za-z0-9-]/', '-', strtolower($team->name));
        $filename = "contacts-{$stem}-" . now()->format('Ymd') . ".csv";

        return response()->streamDownload(function () use ($contacts) {
            $fp = fopen('php://output', 'w');

            fputcsv($fp, ['Name', 'Email', 'Phone', 'Company', 'Job Title', 'Website', 'Address', 'Notes', 'Group', 'Lifecycle Stage', 'Created At']);

            foreach ($contacts as $c) {
                fputcsv($fp, [
                    $c->name,
                    $c->email ?? '',
                    $c->phone ?? '',
                    $c->company ?? '',
                    $c->job_title ?? '',
                    $c->website ?? '',
                    $c->address ?? '',
                    $c->notes ?? '',
                    $c->group?->name ?? '',
                    $c->lifecycle_stage ?? '',
                    $c->created_at?->format('Y-m-d'),
                ]);
            }

            fclose($fp);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
