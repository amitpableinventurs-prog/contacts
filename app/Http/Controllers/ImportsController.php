<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ImportsController extends Controller
{
    /** Supported destination fields when mapping. */
    protected const FIELDS = [
        'name' => 'Name (required)',
        'email' => 'Email',
        'phone' => 'Phone',
        'company' => 'Company',
        'job_title' => 'Job title',
        'website' => 'Website',
        'address' => 'Address',
        'notes' => 'Notes',
        'tags' => 'Tags (comma-separated)',
    ];

    public function form(): View
    {
        Gate::authorize('manage-imports');

        return view('imports.upload', ['fields' => self::FIELDS]);
    }

    /**
     * Stream a CSV template with all importable headers + one example row.
     */
    public function template(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = ['Name', 'Email', 'Phone', 'Company', 'Job title', 'Website', 'Address', 'Notes', 'Tags'];
        $example = [
            'Ada Lovelace',
            'ada@example.com',
            '+1 415 555 0142',
            'Analytical Inc',
            'CTO',
            'https://example.com',
            '10 Downing St, London',
            'Met at Reboot conf — interested in pilot',
            'vip, follow-up',
        ];

        return response()->streamDownload(function () use ($headers, $example) {
            $h = fopen('php://output', 'w');
            fputcsv($h, $headers);
            fputcsv($h, $example);
            fclose($h);
        }, 'contacts-import-template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function preview(Request $request): View|RedirectResponse
    {
        Gate::authorize('manage-imports');

        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt,zip', 'max:10240'],
        ]);

        $uploaded = $request->file('csv');

        // If a ZIP was uploaded, extract the first CSV found inside it.
        if (strtolower($uploaded->getClientOriginalExtension()) === 'zip') {
            if (! class_exists(ZipArchive::class)) {
                return back()->withErrors(['csv' => 'ZIP files are not supported on this server. Please upload a CSV file directly.']);
            }

            $zipPath = $uploaded->store('imports', 'local');
            $zip = new \ZipArchive();
            if ($zip->open(Storage::disk('local')->path($zipPath)) !== true) {
                Storage::disk('local')->delete($zipPath);
                return back()->withErrors(['csv' => 'Could not open ZIP file. Make sure it is a valid ZIP.']);
            }

            $csvEntry = null;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (str_ends_with(strtolower($name), '.csv') && ! str_starts_with(basename($name), '.')) {
                    $csvEntry = $name;
                    break;
                }
            }

            if (! $csvEntry) {
                $zip->close();
                Storage::disk('local')->delete($zipPath);
                return back()->withErrors(['csv' => 'No CSV file found inside the ZIP.']);
            }

            $csvContent = $zip->getFromName($csvEntry);
            $zip->close();
            Storage::disk('local')->delete($zipPath);

            $csvFile = 'imports/' . uniqid('zip-') . '.csv';
            Storage::disk('local')->put($csvFile, $csvContent);
            $stored = $csvFile;
        } else {
            $stored = $uploaded->store('imports', 'local');
        }

        $rows = $this->readCsv(Storage::disk('local')->path($stored), 10);

        if (count($rows) < 2) {
            return back()->withErrors(['csv' => 'CSV is empty or has no data rows.']);
        }

        $headers = array_map(fn ($h) => trim((string) $h), $rows[0]);
        $sample = array_slice($rows, 1, 5);

        $autoMap = [];
        foreach ($headers as $idx => $header) {
            $lower = strtolower($header);
            $autoMap[$idx] = match (true) {
                str_contains($lower, 'name') => 'name',
                str_contains($lower, 'mail') => 'email',
                str_contains($lower, 'phone') || str_contains($lower, 'mobile') || str_contains($lower, 'tel') => 'phone',
                str_contains($lower, 'compan') || $lower === 'organization' => 'company',
                str_contains($lower, 'title') || str_contains($lower, 'job') => 'job_title',
                str_contains($lower, 'web') => 'website',
                str_contains($lower, 'address') => 'address',
                str_contains($lower, 'note') => 'notes',
                str_contains($lower, 'tag') => 'tags',
                default => '',
            };
        }

        $groups = Group::orderBy('name')->get();

        return view('imports.preview', [
            'file' => $stored,
            'headers' => $headers,
            'sample' => $sample,
            'autoMap' => $autoMap,
            'fields' => self::FIELDS,
            'groups' => $groups,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-imports');

        $data = $request->validate([
            'file' => ['required', 'string'],
            'group_id' => ['nullable', 'integer'],
            'has_header' => ['nullable', 'boolean'],
            'mapping' => ['required', 'array'],
            'mapping.*' => ['nullable', 'string'],
        ]);

        $path = Storage::disk('local')->path($data['file']);
        if (! is_file($path)) {
            return redirect()->route('imports.form')->withErrors(['file' => 'Upload expired — please try again.']);
        }

        $rows = $this->readCsv($path);
        $hasHeader = ! empty($data['has_header']);
        $dataRows = $hasHeader ? array_slice($rows, 1) : $rows;
        $mapping = array_filter($data['mapping']);

        if (! in_array('name', $mapping, true)) {
            return back()->withErrors(['mapping' => 'You must map at least one column to "Name".']);
        }

        $teamId = $request->user()->current_team_id;
        $created = 0;
        $skipped = 0;
        $tagCache = [];

        foreach ($dataRows as $row) {
            $attrs = ['team_id' => $teamId, 'owner_id' => $request->user()->id, 'group_id' => $data['group_id'] ?? null];
            $rowTags = [];

            foreach ($mapping as $idx => $field) {
                $value = trim((string) ($row[$idx] ?? ''));
                if ($value === '') continue;

                if ($field === 'tags') {
                    foreach (preg_split('/\s*,\s*/', $value) as $tagName) {
                        if ($tagName === '') continue;
                        $slug = Str::slug($tagName);
                        $tagCache[$slug] ??= Tag::firstOrCreate(
                            ['team_id' => $teamId, 'slug' => $slug],
                            ['name' => $tagName]
                        );
                        $rowTags[] = $tagCache[$slug]->id;
                    }
                } else {
                    $attrs[$field] = $value;
                }
            }

            if (empty($attrs['name'])) {
                $skipped++;
                continue;
            }

            $contact = Contact::create($attrs);
            if ($rowTags) {
                $contact->tags()->sync($rowTags);
            }
            $created++;
        }

        Storage::disk('local')->delete($data['file']);

        return redirect()
            ->route('contacts.index')
            ->with('toast', [
                'type' => 'success',
                'message' => "Imported {$created} contact" . ($created === 1 ? '' : 's') . ($skipped ? " — {$skipped} skipped (missing name)." : '.'),
            ]);
    }

    /** Read up to $limit rows (0 = no limit) from the CSV file. */
    protected function readCsv(string $path, int $limit = 0): array
    {
        $rows = [];
        if (($handle = fopen($path, 'rb')) === false) {
            return $rows;
        }
        $i = 0;
        while (($r = fgetcsv($handle, escape: '\\')) !== false) {
            $rows[] = $r;
            if ($limit && ++$i >= $limit) break;
        }
        fclose($handle);
        return $rows;
    }
}
