<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

    /** Rows per batched INSERT / lookup while processing. */
    protected const BATCH_SIZE = 500;

    /** Soft time budget (seconds) for one processing tick. */
    protected const TICK_SECONDS = 5.0;

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
            'csv' => ['required', 'file', 'mimes:csv,txt,zip', 'max:51200'], // 50 MB
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

            $csvFile = 'imports/' . uniqid('zip-') . '.csv';
            $out = fopen(Storage::disk('local')->path($csvFile), 'wb');
            $in  = $zip->getStream($csvEntry);
            if ($in === false) {
                fclose($out);
                $zip->close();
                Storage::disk('local')->delete($zipPath);
                Storage::disk('local')->delete($csvFile);
                return back()->withErrors(['csv' => 'Could not read the CSV inside the ZIP.']);
            }
            stream_copy_to_stream($in, $out);
            fclose($in);
            fclose($out);
            $zip->close();
            Storage::disk('local')->delete($zipPath);
            $stored = $csvFile;
        } else {
            $stored = $uploaded->store('imports', 'local');
        }

        $preview = $this->buildMappingPreview($stored);
        if (! $preview) {
            return back()->withErrors(['csv' => 'CSV is empty or has no data rows.']);
        }

        return view('imports.preview', $preview);
    }

    /**
     * Re-read a previously-uploaded CSV and build the data the mapping page
     * needs. Used both for the initial upload and to re-render the mapping
     * page (with the user's own column choices preserved) if a later step —
     * e.g. PIN verification in store() — fails validation.
     */
    protected function buildMappingPreview(string $stored, ?array $selectedMapping = null): ?array
    {
        $rows = $this->readCsv(Storage::disk('local')->path($stored), 10);

        if (count($rows) < 2) {
            return null;
        }

        $headers = array_map(fn ($h) => trim((string) $h), $rows[0]);

        $autoMap = [];
        foreach ($headers as $idx => $header) {
            $lower = strtolower($header);
            $autoMap[$idx] = $selectedMapping[$idx] ?? match (true) {
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

        return [
            'file' => $stored,
            'headers' => $headers,
            'sample' => array_slice($rows, 1, 5),
            'autoMap' => $autoMap,
            'fields' => self::FIELDS,
            'groups' => Group::orderBy('name')->get(),
        ];
    }

    /**
     * Validate the mapping and hand off to the chunked progress runner.
     * Large files (lakhs of rows) are processed in small ticks driven by the
     * progress page, so no single request can hit PHP or gateway timeouts.
     */
    public function store(Request $request): View|RedirectResponse
    {
        Gate::authorize('manage-imports');

        $data = $request->validate([
            'file'           => ['required', 'string'],
            'pin'            => ['required', 'string'],
            'group_id'       => ['nullable', 'integer'],
            'has_header'     => ['nullable', 'boolean'],
            'mapping'        => ['required', 'array'],
            'mapping.*'      => ['nullable', 'string'],
            'overwrite_by_phone'   => ['nullable', 'boolean'],
            'overwrite_empty_only' => ['nullable', 'boolean'],
        ]);

        $relative = $data['file'];
        $path = Storage::disk('local')->path($relative);
        if (! str_starts_with($relative, 'imports/') || str_contains($relative, '..') || ! is_file($path)) {
            return redirect()->route('imports.form')->withErrors(['file' => 'Upload expired — please try again.']);
        }

        if ($data['pin'] !== env('EXPORT_PIN')) {
            $preview = $this->buildMappingPreview($relative, $data['mapping']);
            if (! $preview) {
                return redirect()->route('imports.form')->withErrors(['file' => 'Upload expired — please try again.']);
            }

            return view('imports.preview', $preview)->withErrors(['pin' => 'Incorrect PIN.']);
        }

        $mapping = array_filter($data['mapping']);
        if (! in_array('name', $mapping, true)) {
            return back()->withErrors(['mapping' => 'You must map at least one column to "Name".']);
        }

        $canOverwrite = Gate::allows('contacts.update');

        $id = Str::random(24);
        $this->saveState($id, [
            'file'                 => $relative,
            'total_bytes'          => filesize($path),
            'mapping'              => $mapping,
            'group_id'             => $data['group_id'] ?? null,
            'has_header'           => ! empty($data['has_header']),
            'overwrite_by_phone'   => $canOverwrite && ! empty($data['overwrite_by_phone']),
            'overwrite_empty_only' => ! empty($data['overwrite_empty_only']),
            'team_id'              => $request->user()->current_team_id,
            'user_id'              => $request->user()->id,
            'offset'               => 0,
            'processed'            => 0,
            'created'              => 0,
            'updated'              => 0,
            'skipped'              => 0,
            'done'                 => false,
        ]);

        return redirect()->route('imports.progress', ['import' => $id]);
    }

    /** Progress page — drives processing ticks via fetch() until done. */
    public function progress(string $import): View|RedirectResponse
    {
        Gate::authorize('manage-imports');

        $state = $this->loadState($import);
        if (! $state || $state['user_id'] !== Auth::id()) {
            return redirect()->route('imports.form')->withErrors(['csv' => 'Import not found or already finished.']);
        }

        return view('imports.progress', ['importId' => $import, 'state' => $state]);
    }

    /**
     * Process one slice of the CSV (a few thousand rows / ~5 seconds max)
     * and persist the file offset so the next tick resumes where we stopped.
     */
    public function process(string $import): JsonResponse
    {
        Gate::authorize('manage-imports');

        $state = $this->loadState($import);
        if (! $state || $state['user_id'] !== Auth::id()) {
            return response()->json(['error' => 'Import not found or already finished.'], 404);
        }
        if ($state['done']) {
            return response()->json($this->progressPayload($state));
        }

        $path = Storage::disk('local')->path($state['file']);
        if (! is_file($path)) {
            $this->deleteState($import);
            return response()->json(['error' => 'Import file is missing — please upload again.'], 410);
        }

        // Guard against overlapping ticks (double tabs, impatient retries).
        $lock = null;
        try {
            $lock = Cache::lock("import-tick:{$import}", 60);
            if (! $lock->get()) {
                return response()->json($this->progressPayload($state) + ['locked' => true]);
            }
        } catch (\Throwable) {
            $lock = null; // cache store without lock support — proceed unguarded
        }

        try {
            set_time_limit(120);
            $state = $this->processTick($import, $state, $path);

            $payload = $this->progressPayload($state);

            if ($state['done']) {
                Storage::disk('local')->delete($state['file']);
                $this->deleteState($import);
                // Shown by the contacts page the progress screen redirects to.
                session()->flash('toast', ['type' => 'success', 'message' => $payload['message']]);
            }

            return response()->json($payload);
        } finally {
            $lock?->release();
        }
    }

    /** Run one time-boxed processing tick; returns the updated state. */
    protected function processTick(string $import, array $state, string $path): array
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new \RuntimeException('Could not open import file.');
        }

        $start = microtime(true);

        if ($state['offset'] > 0) {
            fseek($handle, $state['offset']);
        } elseif ($state['has_header']) {
            fgetcsv($handle, escape: '\\'); // skip header row
        }

        while (microtime(true) - $start < self::TICK_SECONDS) {
            $batch = [];
            while (count($batch) < self::BATCH_SIZE
                && ($row = fgetcsv($handle, escape: '\\')) !== false) {
                $batch[] = array_map([$this, 'toUtf8'], $row);
            }

            $eof = count($batch) < self::BATCH_SIZE;

            if ($batch) {
                $this->flushBatch($batch, $state);
                $state['offset'] = ftell($handle);
            }
            if ($eof) {
                $state['done'] = true;
            }

            // Persist after every committed batch so an aborted tick
            // resumes from the last flush instead of re-importing rows.
            $this->saveState($import, $state);

            if ($eof) {
                break;
            }
        }

        fclose($handle);

        return $state;
    }

    /**
     * Import one batch of parsed CSV rows: new contacts are bulk-inserted,
     * overwrite matches are found with a single indexed phone lookup.
     * Counters in $state are updated by reference semantics (returned array).
     */
    protected function flushBatch(array $batch, array &$state): void
    {
        $teamId  = $state['team_id'];
        $mapping = $state['mapping'];

        // ── Parse rows into attribute arrays ────────────────────────────
        $rows = [];
        foreach ($batch as $row) {
            $attrs = [
                'team_id'         => $teamId,
                'owner_id'        => $state['user_id'],
                'group_id'        => $state['group_id'],
                'approval_status' => 'approved',
            ];
            $rowTags = [];

            foreach ($mapping as $idx => $field) {
                $value = trim((string) ($row[$idx] ?? ''));
                // Treat empty strings and pandas NaN exports as missing
                if ($value === '' || strtolower($value) === 'nan') continue;

                if ($field === 'tags') {
                    foreach (preg_split('/\s*,\s*/', $value) as $tagName) {
                        if ($tagName !== '') $rowTags[] = $tagName;
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
                $state['skipped']++;
                $state['processed']++;
                continue;
            }

            $attrs['phone_digits'] = Contact::normalizePhone($attrs['phone'] ?? null);
            $rows[] = ['attrs' => $attrs, 'tags' => $rowTags];
        }

        if (! $rows) {
            return;
        }

        $tagIdsFor = $this->resolveTagIds($rows, $teamId);

        // ── Find existing contacts for overwrite mode (one indexed query) ─
        $existingByDigits = collect();
        if ($state['overwrite_by_phone']) {
            $digits = collect($rows)->pluck('attrs.phone_digits')->filter()->unique()->values();
            if ($digits->isNotEmpty()) {
                $existingByDigits = Contact::where('team_id', $teamId)
                    ->whereIn('phone_digits', $digits)
                    ->get()
                    ->keyBy('phone_digits');
            }
        }

        // Bulk INSERT requires identical columns on every row — pad the
        // union of mapped fields with nulls.
        $insertColumns = array_fill_keys(
            array_merge(
                ['team_id', 'owner_id', 'group_id', 'approval_status', 'phone_digits'],
                array_values(array_diff($mapping, ['tags']))
            ),
            null
        );

        DB::transaction(function () use ($rows, $existingByDigits, $tagIdsFor, $insertColumns, &$state) {
            $now = now();
            $toInsert = [];

            foreach ($rows as $i => $row) {
                $attrs   = $row['attrs'];
                $tagIds  = $tagIdsFor($i);
                $existing = $attrs['phone_digits']
                    ? $existingByDigits->get($attrs['phone_digits'])
                    : null;

                if ($existing) {
                    // Overwrite: empty-only mode fills only blank fields; otherwise overwrites all.
                    $updateAttrs = [];
                    foreach ($attrs as $field => $value) {
                        if (in_array($field, ['team_id', 'owner_id', 'phone_digits'])) continue;
                        if ($state['overwrite_empty_only'] && ! empty($existing->$field)) continue;
                        $updateAttrs[$field] = $value;
                    }
                    if (! empty($updateAttrs)) {
                        $existing->update($updateAttrs);
                    }
                    if ($tagIds) {
                        $existing->tags()->syncWithoutDetaching($tagIds);
                    }
                    $state['updated']++;
                } elseif ($tagIds) {
                    // Needs the new id for the pivot rows — create individually.
                    $contact = Contact::create($attrs);
                    $contact->tags()->sync($tagIds);
                    $state['created']++;
                } else {
                    $toInsert[] = array_merge($insertColumns, $attrs, ['created_at' => $now, 'updated_at' => $now]);
                    $state['created']++;
                }
                $state['processed']++;
            }

            if ($toInsert) {
                Contact::insert($toInsert);
            }
        });
    }

    /**
     * Resolve tag names to ids once per batch (creating missing tags when
     * allowed). Returns a closure: (rowIndex) => int[] of tag ids.
     */
    protected function resolveTagIds(array $rows, int $teamId): \Closure
    {
        static $tagCache = [];

        $canCreateTags = Gate::allows('manage-tags');

        $resolved = [];
        foreach ($rows as $i => $row) {
            $ids = [];
            foreach ($row['tags'] as $tagName) {
                $slug = Str::slug($tagName);
                if ($slug === '') continue;
                $cacheKey = $teamId . ':' . $slug;
                if (! array_key_exists($cacheKey, $tagCache)) {
                    $tagCache[$cacheKey] = $canCreateTags
                        ? Tag::firstOrCreate(['team_id' => $teamId, 'slug' => $slug], ['name' => $tagName])
                        : Tag::where('team_id', $teamId)->where('slug', $slug)->first();
                }
                if ($tagCache[$cacheKey]) {
                    $ids[] = $tagCache[$cacheKey]->id;
                }
            }
            $resolved[$i] = array_values(array_unique($ids));
        }

        return fn (int $i) => $resolved[$i] ?? [];
    }

    // ------------------------------------------------------------------
    // Import state persistence (JSON sidecar next to the uploaded CSV)
    // ------------------------------------------------------------------

    protected function statePath(string $id): ?string
    {
        if (! preg_match('/^[A-Za-z0-9]{24}$/', $id)) {
            return null;
        }

        return "imports/{$id}.state.json";
    }

    protected function loadState(string $id): ?array
    {
        $path = $this->statePath($id);
        if (! $path || ! Storage::disk('local')->exists($path)) {
            return null;
        }

        $state = json_decode(Storage::disk('local')->get($path), true);

        return is_array($state) ? $state : null;
    }

    protected function saveState(string $id, array $state): void
    {
        Storage::disk('local')->put($this->statePath($id), json_encode($state));
    }

    protected function deleteState(string $id): void
    {
        Storage::disk('local')->delete($this->statePath($id));
    }

    protected function progressPayload(array $state): array
    {
        $percent = $state['done']
            ? 100
            : ($state['total_bytes'] > 0
                ? min(99, (int) floor($state['offset'] / $state['total_bytes'] * 100))
                : 0);

        $msg = "Imported {$state['created']} new contact" . ($state['created'] === 1 ? '' : 's');
        if ($state['updated']) $msg .= ", updated {$state['updated']}";
        if ($state['skipped']) $msg .= ", skipped {$state['skipped']} (missing name)";

        return [
            'done'      => $state['done'],
            'percent'   => $percent,
            'processed' => $state['processed'],
            'created'   => $state['created'],
            'updated'   => $state['updated'],
            'skipped'   => $state['skipped'],
            'message'   => $msg . '.',
        ];
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
