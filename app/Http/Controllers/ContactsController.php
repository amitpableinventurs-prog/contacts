<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactFile;
use App\Models\ContactGalleryImage;
use App\Models\ContactNote;
use App\Models\Group;
use App\Models\Tag;
use App\Services\AnthropicClient;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContactsController extends Controller
{
    /**
     * Ensure the contact belongs to the user's current team.
     * Super Admin bypasses team scoping (can act across all teams).
     */
    private function ensureSameTeam(Contact $contact): void
    {
        if (Auth::user()->isSuperAdmin()) {
            return;
        }
        abort_unless($contact->team_id === Auth::user()->current_team_id, 403);
    }

    // ------------------------------------------------------------------
    // List
    // ------------------------------------------------------------------

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Contact::class);

        $teamId = Auth::user()->current_team_id;

        $query = Contact::where('team_id', $teamId)
            ->with(['group', 'tags', 'owner']);

        // ── Basic search ──────────────────────────────────────────────────
        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name',    'like', "%{$q}%")
                    ->orWhere('email',   'like', "%{$q}%")
                    ->orWhere('phone',   'like', "%{$q}%")
                    ->orWhere('company', 'like', "%{$q}%")
                    ->orWhere('city',    'like', "%{$q}%");
            });
        }

        // ── Number search ─────────────────────────────────────────────────
        if ($number = trim((string) $request->input('number'))) {
            $query->where('phone', 'like', "%{$number}%");
        }

        // ── Group filter ──────────────────────────────────────────────────
        if ($groupId = $request->input('group_id')) {
            $query->where('group_id', $groupId);
        }

        // ── Tag filter ────────────────────────────────────────────────────
        if ($tagIds = $request->input('tags')) {
            foreach ((array) $tagIds as $tagId) {
                $query->whereHas('tags', fn ($t) => $t->where('tags.id', $tagId));
            }
        }

        // ── Advanced filters ──────────────────────────────────────────────
        if ($company = trim((string) $request->input('company'))) {
            $query->where('company', 'like', "%{$company}%");
        }
        if ($jobTitle = trim((string) $request->input('job_title'))) {
            $query->where('job_title', 'like', "%{$jobTitle}%");
        }
        if ($city = trim((string) $request->input('city'))) {
            $query->where('city', 'like', "%{$city}%");
        }
        if ($stage = $request->input('lifecycle_stage')) {
            $query->where('lifecycle_stage', $stage);
        }
        if ($ratingMin = $request->input('rating_min')) {
            $query->where('rating', '>=', (int) $ratingMin);
        }
        if ($ratingMax = $request->input('rating_max')) {
            $query->where('rating', '<=', (int) $ratingMax);
        }
        if ($hasPhone = $request->input('has_phone')) {
            $hasPhone === 'yes'
                ? $query->whereNotNull('phone')->where('phone', '!=', '')
                : $query->where(fn ($s) => $s->whereNull('phone')->orWhere('phone', ''));
        }
        if ($hasEmail = $request->input('has_email')) {
            $hasEmail === 'yes'
                ? $query->whereNotNull('email')->where('email', '!=', '')
                : $query->where(fn ($s) => $s->whereNull('email')->orWhere('email', ''));
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        if ($lastFrom = $request->input('last_contacted_from')) {
            $query->whereDate('last_contacted_at', '>=', $lastFrom);
        }
        if ($lastTo = $request->input('last_contacted_to')) {
            $query->whereDate('last_contacted_at', '<=', $lastTo);
        }

        // By default show only approved contacts. Pending ones go to /contacts/pending.
        $query->where('approval_status', '!=', 'pending');

        $contacts     = $query->orderBy('name')->paginate(25)->withQueryString();
        $groups       = Group::where('team_id', $teamId)->orderBy('name')->get();
        $tags         = Tag::where('team_id', $teamId)->orderBy('name')->get();
        $pendingCount = Auth::user()->isClerk() ? 0 : Contact::where('team_id', $teamId)->where('approval_status', 'pending')->count();

        return view('contacts.index', compact('contacts', 'groups', 'tags', 'pendingCount'));
    }

    // ------------------------------------------------------------------
    // Autocomplete (JSON)
    // ------------------------------------------------------------------

    public function autocomplete(Request $request): JsonResponse
    {
        $q = $request->string('q')->trim();
        if ($q->length() < 2) {
            return response()->json([]);
        }

        $teamId = Auth::user()->current_team_id;

        return response()->json(
            Contact::where('team_id', $teamId)
                ->where(fn ($query) => $query
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                )
                ->orderBy('name')
                ->limit(10)
                ->get(['id', 'name', 'phone', 'email'])
        );
    }

    // ------------------------------------------------------------------
    // Create / Store
    // ------------------------------------------------------------------

    public function create(): View
    {
        Gate::authorize('create', Contact::class);

        $teamId = Auth::user()->current_team_id;
        $groups = Group::where('team_id', $teamId)->orderBy('name')->get();
        $tags   = Tag::where('team_id', $teamId)->orderBy('name')->get();

        return view('contacts.create', compact('groups', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Contact::class);

        $data = $this->validateContact($request);

        // Clerk-created contacts require manager approval before becoming active.
        $approvalStatus = Auth::user()->isClerk() ? 'pending' : 'approved';

        $contact = Contact::create([
            ...$data,
            'team_id'         => Auth::user()->current_team_id,
            'owner_id'        => Auth::id(),
            'approval_status' => $approvalStatus,
            'approved_by'     => $approvalStatus === 'approved' ? Auth::id() : null,
            'approved_at'     => $approvalStatus === 'approved' ? now() : null,
        ]);

        $this->handlePhoto($request, $contact);

        if ($tagIds = $request->input('tags', [])) {
            $contact->tags()->sync($tagIds);
        }

        ActivityLogger::log('contact.created', $contact, ['name' => $contact->name, 'approval' => $approvalStatus]);

        $msg = $approvalStatus === 'pending'
            ? "{$contact->name} submitted — awaiting manager approval."
            : "{$contact->name} created.";

        return redirect()->route('contacts.show', $contact)
            ->with('toast', ['type' => 'success', 'message' => $msg]);
    }

    // ------------------------------------------------------------------
    // Show
    // ------------------------------------------------------------------

    public function show(Contact $contact): View
    {
        Gate::authorize('view', $contact);

        $contact->load(['group', 'tags', 'owner', 'contactNotes.author', 'files', 'galleryImages']);

        $activity = $contact->messages()->latest('created_at')->limit(50)->get();
        $emails   = $contact->emails()->latest('created_at')->limit(50)->get();

        $duplicateCount = Contact::where('team_id', $contact->team_id)
            ->where('id', '!=', $contact->id)
            ->where(function ($q) use ($contact) {
                if ($contact->email) {
                    $q->orWhere('email', $contact->email);
                }
                if ($contact->phone) {
                    $q->orWhere('phone', $contact->phone);
                }
            })
            ->count();

        return view('contacts.show', compact('contact', 'activity', 'emails', 'duplicateCount'));
    }

    // ------------------------------------------------------------------
    // Edit / Update
    // ------------------------------------------------------------------

    public function edit(Contact $contact): View
    {
        Gate::authorize('update', $contact);

        $teamId = Auth::user()->current_team_id;
        $groups = Group::where('team_id', $teamId)->orderBy('name')->get();
        $tags   = Tag::where('team_id', $teamId)->orderBy('name')->get();
        $contact->load('tags');

        return view('contacts.edit', compact('contact', 'groups', 'tags'));
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        Gate::authorize('update', $contact);

        $data = $this->validateContact($request);
        $contact->update($data);

        $this->handlePhoto($request, $contact);

        $contact->tags()->sync($request->input('tags', []));

        ActivityLogger::log('contact.updated', $contact, ['name' => $contact->name]);

        return redirect()->route('contacts.show', $contact)
            ->with('toast', ['type' => 'success', 'message' => 'Contact updated.']);
    }

    // ------------------------------------------------------------------
    // Delete
    // ------------------------------------------------------------------

    public function destroy(Contact $contact): RedirectResponse
    {
        Gate::authorize('delete', $contact);

        $name = $contact->name;
        ActivityLogger::log('contact.trashed', $contact, ['name' => $name]);
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('toast', ['type' => 'success', 'message' => "{$name} deleted."]);
    }

    // ------------------------------------------------------------------
    // Suspend / Ban
    // ------------------------------------------------------------------

    public function suspend(Contact $contact): RedirectResponse
    {
        Gate::authorize('update', $contact);
        $contact->update(['status' => 'suspended', 'suspended_at' => now()]);
        return back()->with('toast', ['type' => 'success', 'message' => "{$contact->name} suspended."]);
    }

    public function ban(Contact $contact): RedirectResponse
    {
        Gate::authorize('update', $contact);
        $contact->update(['status' => 'banned', 'banned_at' => now()]);
        return back()->with('toast', ['type' => 'success', 'message' => "{$contact->name} banned."]);
    }

    // ------------------------------------------------------------------
    // Approval workflow (Clerk submits → Manager approves/rejects)
    // ------------------------------------------------------------------

    public function pending(): \Illuminate\View\View
    {
        Gate::authorize('approve-contacts');

        $contacts = Contact::where('approval_status', 'pending')
            ->when(! Auth::user()->isSuperAdmin(), fn ($q) =>
                $q->where('team_id', Auth::user()->current_team_id)
            )
            ->with(['owner', 'group', 'tags'])
            ->latest()
            ->paginate(25);

        return view('contacts.pending', compact('contacts'));
    }

    public function approve(Contact $contact): RedirectResponse
    {
        Gate::authorize('approve-contacts');
        $this->ensureSameTeam($contact);

        $contact->update([
            'approval_status' => 'approved',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        ActivityLogger::log('contact.approved', $contact, ['name' => $contact->name]);

        return back()->with('toast', ['type' => 'success', 'message' => "{$contact->name} approved."]);
    }

    public function reject(Contact $contact): RedirectResponse
    {
        Gate::authorize('approve-contacts');
        $this->ensureSameTeam($contact);

        $contact->update(['approval_status' => 'rejected']);
        ActivityLogger::log('contact.rejected', $contact, ['name' => $contact->name]);

        return back()->with('toast', ['type' => 'success', 'message' => "{$contact->name} rejected."]);
    }

    // ------------------------------------------------------------------
    // Rating
    // ------------------------------------------------------------------

    public function rate(Request $request, Contact $contact): RedirectResponse
    {
        Gate::authorize('rate', $contact);

        $request->validate(['rating' => ['required', 'numeric', 'min:0', 'max:5']]);
        $contact->update(['rating' => $request->input('rating')]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Rating saved.']);
    }

    // ------------------------------------------------------------------
    // Notes (relational)
    // ------------------------------------------------------------------

    public function storeNote(Request $request, Contact $contact): RedirectResponse
    {
        Gate::authorize('addNote', $contact);

        $request->validate(['note_html' => ['required', 'string', 'max:10000']]);

        ContactNote::create([
            'team_id'    => $contact->team_id,
            'contact_id' => $contact->id,
            'user_id'    => Auth::id(),
            'note_html'  => $request->input('note_html'),
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Note added.']);
    }

    public function destroyNote(Contact $contact, ContactNote $note): RedirectResponse
    {
        Gate::authorize('update', $contact);
        abort_unless($note->contact_id === $contact->id, 403);

        $note->delete();
        return back()->with('toast', ['type' => 'success', 'message' => 'Note deleted.']);
    }

    // ------------------------------------------------------------------
    // Files
    // ------------------------------------------------------------------

    public function storeFile(Request $request, Contact $contact): RedirectResponse
    {
        Gate::authorize('update', $contact);

        $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10 MB
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store("contacts/{$contact->id}/files", 'public');

        ContactFile::create([
            'team_id'    => $contact->team_id,
            'contact_id' => $contact->id,
            'user_id'    => Auth::id(),
            'file_name'  => $uploaded->getClientOriginalName(),
            'file_path'  => $path,
            'mime_type'  => $uploaded->getMimeType(),
            'size_bytes' => $uploaded->getSize(),
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'File uploaded.']);
    }

    public function destroyFile(Contact $contact, ContactFile $file): RedirectResponse
    {
        Gate::authorize('update', $contact);
        abort_unless($file->contact_id === $contact->id, 403);

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'File deleted.']);
    }

    // ------------------------------------------------------------------
    // Gallery
    // ------------------------------------------------------------------

    public function storeGallery(Request $request, Contact $contact): RedirectResponse
    {
        Gate::authorize('update', $contact);

        $request->validate([
            'images'   => ['required', 'array'],
            'images.*' => ['image', 'max:5120'], // 5 MB each
        ]);

        foreach ($request->file('images', []) as $image) {
            $path = $image->store("contacts/{$contact->id}/gallery", 'public');
            ContactGalleryImage::create([
                'team_id'    => $contact->team_id,
                'contact_id' => $contact->id,
                'user_id'    => Auth::id(),
                'image_path' => $path,
                'image_name' => $image->getClientOriginalName(),
                'size_bytes' => $image->getSize(),
            ]);
        }

        return back()->with('toast', ['type' => 'success', 'message' => 'Images uploaded.']);
    }

    public function destroyGallery(Contact $contact, ContactGalleryImage $image): RedirectResponse
    {
        Gate::authorize('update', $contact);
        abort_unless($image->contact_id === $contact->id, 403);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Image deleted.']);
    }

    // ------------------------------------------------------------------
    // Merge
    // ------------------------------------------------------------------

    public function merge(Contact $contact): View
    {
        Gate::authorize('update', $contact);

        $phone = $contact->phone;

        $duplicates = Contact::where('team_id', $contact->team_id)
            ->where('id', '!=', $contact->id)
            ->where(function ($q) use ($contact, $phone) {
                if ($contact->email) $q->orWhere('email', $contact->email);
                if ($phone) $q->orWhere('phone', $phone);
            })
            ->with('tags')
            ->get();

        return view('contacts.merge', compact('contact', 'duplicates'));
    }

    public function mergeStore(Request $request, Contact $contact): RedirectResponse
    {
        Gate::authorize('update', $contact);

        $duplicateIds = $request->input('duplicate_ids', []);
        if (empty($duplicateIds)) {
            return back()->with('toast', ['type' => 'error', 'message' => 'No contacts selected.']);
        }

        $duplicates = Contact::where('team_id', $contact->team_id)
            ->whereIn('id', $duplicateIds)
            ->with('tags')
            ->get();

        DB::transaction(function () use ($contact, $duplicates) {
            $fillable = ['phone', 'email', 'company', 'job_title', 'website', 'address', 'photo', 'birthday', 'lifecycle_stage', 'facebook', 'twitter', 'linkedin', 'notes', 'description_html'];
            $tagIds = $contact->tags->pluck('id')->all();

            foreach ($duplicates as $dup) {
                foreach ($fillable as $field) {
                    if (empty($contact->$field) && ! empty($dup->$field)) {
                        $contact->$field = $dup->$field;
                    }
                }
                $tagIds = array_unique(array_merge($tagIds, $dup->tags->pluck('id')->all()));
                $dup->messages()->update(['contact_id' => $contact->id]);
                $dup->emails()->update(['contact_id' => $contact->id]);
                $dup->contactNotes()->update(['contact_id' => $contact->id]);
                $dup->files()->update(['contact_id' => $contact->id]);
                $dup->galleryImages()->update(['contact_id' => $contact->id]);
                $dup->forceDelete();
            }
            $contact->save();
            $contact->tags()->sync($tagIds);
        });

        return redirect()->route('contacts.show', $contact)
            ->with('toast', ['type' => 'success', 'message' => 'Contacts merged.']);
    }

    // ------------------------------------------------------------------
    // Bulk operations
    // ------------------------------------------------------------------

    public function bulk(Request $request): RedirectResponse
    {
        Gate::authorize('viewAny', Contact::class);

        $teamId = Auth::user()->current_team_id;
        $action = $request->input('action');
        $ids    = $request->input('contact_ids', []);

        if (empty($ids)) {
            return back()->with('toast', ['type' => 'error', 'message' => 'No contacts selected.']);
        }

        $contacts = Contact::where('team_id', $teamId)->whereIn('id', $ids);

        // Clerks may only trash contacts — block group/tag assignment.
        if (in_array($action, ['group', 'tag']) && Auth::user()->isClerk()) {
            abort(403, 'Clerks cannot assign groups or tags.');
        }

        match ($action) {
            'group'  => $contacts->update(['group_id' => $request->input('group_id')]),
            'tag'    => $contacts->get()->each(fn ($c) => $c->tags()->syncWithoutDetaching([$request->input('tag_id')])),
            'delete' => $contacts->get()->each->delete(),
            default  => null,
        };

        $label = match ($action) {
            'group'  => 'Group updated.',
            'tag'    => 'Tag added.',
            'delete' => count($ids).' contact(s) deleted.',
            default  => 'Done.',
        };

        return back()->with('toast', ['type' => 'success', 'message' => $label]);
    }

    // ------------------------------------------------------------------
    // AI: Enrich from text
    // ------------------------------------------------------------------

    public function enrich(Request $request, AnthropicClient $ai): JsonResponse
    {
        $request->validate(['text' => ['required', 'string', 'max:5000']]);
        $fields = $ai->extractContact($request->input('text'));
        return response()->json(['fake' => $ai->isFake(), 'fields' => $fields]);
    }

    // ------------------------------------------------------------------
    // AI: Suggest tags
    // ------------------------------------------------------------------

    public function suggestTags(Request $request, AnthropicClient $ai): JsonResponse
    {
        $request->validate(['text' => ['required', 'string', 'max:5000']]);

        $teamId  = Auth::user()->current_team_id;
        $allTags = Tag::where('team_id', $teamId)->get(['id', 'name', 'slug'])->toArray();
        $slugs   = $ai->suggestTags($request->input('text'), $allTags);

        $matched = Tag::where('team_id', $teamId)->whereIn('slug', $slugs)->get(['id', 'name', 'slug']);

        return response()->json(['fake' => $ai->isFake(), 'tags' => $matched]);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function validateContact(Request $request): array
    {
        $data = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['nullable', 'email', 'max:255'],
            'phone'                => ['nullable', 'string', 'max:50'],
            'company'              => ['nullable', 'string', 'max:255'],
            'job_title'            => ['nullable', 'string', 'max:255'],
            'website'              => ['nullable', 'url', 'max:255'],
            'address'              => ['nullable', 'string', 'max:500'],
            'city'                 => ['nullable', 'string', 'max:120'],
            'birthday'             => ['nullable', 'date'],
            'lifecycle_stage'      => ['nullable', 'in:lead,prospect,customer,partner,vendor'],
            'group_id'             => ['nullable', 'exists:groups,id'],
            'rating'               => ['nullable', 'numeric', 'min:0', 'max:5'],
            'status'               => ['nullable', 'in:active,suspended,banned'],
            'facebook'             => ['nullable', 'string', 'max:255'],
            'twitter'              => ['nullable', 'string', 'max:255'],
            'linkedin'             => ['nullable', 'string', 'max:255'],
            'notes'                => ['nullable', 'string'],
            'description_html'     => ['nullable', 'string'],
            'custom_fields_keys'   => ['nullable', 'array'],
            'custom_fields_keys.*' => ['nullable', 'string', 'max:100'],
            'custom_fields_values' => ['nullable', 'array'],
        ]);

        $keys   = $data['custom_fields_keys'] ?? [];
        $values = $request->input('custom_fields_values', []);
        $custom = [];
        foreach ($keys as $i => $key) {
            if (filled($key)) {
                $custom[$key] = $values[$i] ?? '';
            }
        }
        unset($data['custom_fields_keys'], $data['custom_fields_values']);
        $data['custom_fields'] = $custom ?: null;

        return $data;
    }

    private function handlePhoto(Request $request, Contact $contact): void
    {
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($contact->photo) {
                Storage::disk('public')->delete($contact->photo);
            }
            $path = $request->file('photo')->store("contacts/{$contact->id}", 'public');
            $contact->update(['photo' => $path]);
        }
    }
}
