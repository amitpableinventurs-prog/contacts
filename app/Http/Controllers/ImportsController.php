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
        'city' => 'City',
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
                str_contains($lower, 'city') || str_contains($lower, 'town') => 'city',
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
            'file'           => ['required', 'string'],
            'group_id'       => ['nullable', 'integer'],
            'has_header'     => ['nullable', 'boolean'],
            'mapping'        => ['required', 'array'],
            'mapping.*'      => ['nullable', 'string'],
            'overwrite_by_phone'   => ['nullable', 'boolean'],
            'overwrite_empty_only' => ['nullable', 'boolean'],
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

        $teamId          = $request->user()->current_team_id;
        // Users who can't edit contacts (e.g. Clerks) may only create new
        // contacts via import, never overwrite existing ones.
        $canOverwrite    = Gate::allows('contacts.update');
        $canCreateTags   = Gate::allows('manage-tags');
        $overwriteByPhone  = $canOverwrite && ! empty($data['overwrite_by_phone']);
        $overwriteEmptyOnly = ! empty($data['overwrite_empty_only']);
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $tagCache = [];

        foreach ($dataRows as $row) {
            $attrs   = ['team_id' => $teamId, 'owner_id' => $request->user()->id, 'group_id' => $data['group_id'] ?? null, 'approval_status' => 'approved'];
            $rowTags = [];

            foreach ($mapping as $idx => $field) {
                $value = trim((string) ($row[$idx] ?? ''));
                // Treat empty strings and pandas NaN exports as missing
                if ($value === '' || strtolower($value) === 'nan') continue;

                if ($field === 'tags') {
                    foreach (preg_split('/\s*,\s*/', $value) as $tagName) {
                        if ($tagName === '') continue;
                        $slug = Str::slug($tagName);
                        if (! array_key_exists($slug, $tagCache)) {
                            $tagCache[$slug] = $canCreateTags
                                ? Tag::firstOrCreate(['team_id' => $teamId, 'slug' => $slug], ['name' => $tagName])
                                : Tag::where('team_id', $teamId)->where('slug', $slug)->first();
                        }
                        if ($tagCache[$slug]) {
                            $rowTags[] = $tagCache[$slug]->id;
                        }
                    }
                } elseif ($field === 'phone') {
                    $attrs[$field] = preg_replace('/[^\d+]/', '', $value);
                } else {
                    $attrs[$field] = $value;
                }
            }

            // Treat names made only of ?, ., spaces as blank (garbage from CSV exports)
            if (isset($attrs['name']) && preg_match('/^[\?\.\s]+$/', $attrs['name'])) {
                unset($attrs['name']);
            }

            if (empty($attrs['name'])) {
                $skipped++;
                continue;
            }

            // Try to find existing contact by phone when overwrite mode is on.
            $existing = null;
            if ($overwriteByPhone && ! empty($attrs['phone'])) {
                $existing = Contact::where('team_id', $teamId)
                    ->where('phone', $attrs['phone'])
                    ->first();

                // Fallback: strip all non-digits from stored phones and compare
                if (! $existing) {
                    $digitsOnly = preg_replace('/\D/', '', $attrs['phone']);
                    if ($digitsOnly !== '') {
                        $existing = Contact::where('team_id', $teamId)
                            ->get()
                            ->first(fn ($c) => preg_replace('/\D/', '', (string) $c->phone) === $digitsOnly);
                    }
                }
            }

            if ($existing) {
                // Overwrite: empty-only mode fills only blank fields; otherwise overwrites all.
                $updateAttrs = [];
                foreach ($attrs as $field => $value) {
                    if (in_array($field, ['team_id', 'owner_id'])) continue;
                    if ($overwriteEmptyOnly && ! empty($existing->$field)) continue;
                    $updateAttrs[$field] = $value;
                }
                if (! empty($updateAttrs)) {
                    $existing->update($updateAttrs);
                }
                if ($rowTags) {
                    $existing->tags()->syncWithoutDetaching($rowTags);
                }
                $updated++;
            } else {
                $contact = Contact::create($attrs);
                if ($rowTags) {
                    $contact->tags()->sync($rowTags);
                }
                $created++;
            }
        }

        Storage::disk('local')->delete($data['file']);

        $msg = "Imported {$created} new contact" . ($created === 1 ? '' : 's');
        if ($updated) $msg .= ", updated {$updated}";
        if ($skipped) $msg .= ", skipped {$skipped} (missing name)";

        return redirect()
            ->route('contacts.index')
            ->with('toast', ['type' => 'success', 'message' => $msg . '.']);
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
            $rows[] = array_map([$this, 'toUtf8'], $r);
            if ($limit && ++$i >= $limit) break;
        }
        fclose($handle);
        return $rows;
    }

    /** Convert a CSV cell value to valid UTF-8, handling Windows-1252 / Latin-1 files. */
    protected function toUtf8(?string $value): ?string
    {
        if ($value === null) return null;

        // Strip UTF-8 BOM if present on the first cell
        if (str_starts_with($value, "\xEF\xBB\xBF")) {
            $value = substr($value, 3);
        }

        // Already valid UTF-8 — return as-is
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        // Fall back to Windows-1252 → UTF-8 conversion
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }
}
