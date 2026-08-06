<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\ContactEditRequest;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ContactsApiController extends Controller
{
    /** Ensure the contact belongs to the user's current team. Super Admin bypasses. */
    private function ensureSameTeam(Contact $contact): void
    {
        if (Auth::user()->isSuperAdmin()) {
            return;
        }
        abort_unless($contact->team_id === Auth::user()->current_team_id, 403);
    }

    // Mirrors ContactsController::scopeVisibleContactsForList() on the web side —
    // a Manager's own pending submissions stay visible to them; other pending
    // contacts are hidden from everyone until Admin+ approves them.
    private function scopeVisibleContactsForList(Builder $query, User $user): void
    {
        if ($user->isManager()) {
            $query->where(function ($visible) use ($user) {
                $visible
                    ->where(function ($pending) use ($user) {
                        $pending->where('approval_status', 'pending')->where('owner_id', $user->id);
                    })
                    ->orWhere(function ($approved) use ($user) {
                        $approved->where(function ($status) {
                            $status->whereNull('approval_status')->orWhere('approval_status', '!=', 'pending');
                        })->where(function ($owner) use ($user) {
                            $owner->whereNull('owner_id')->orWhere('owner_id', '!=', $user->id);
                        });
                    });
            });

            return;
        }

        $query->where(function ($status) {
            $status->whereNull('approval_status')->orWhere('approval_status', '!=', 'pending');
        });
    }

    public function index(Request $request): JsonResource
    {
        Gate::authorize('viewAny', Contact::class);

        $user = $request->user();
        $teamId = $user->current_team_id;
        $isClerk = $user->isClerk();
        $hasAdvancedSearch = $user->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN);

        $query = Contact::where('team_id', $teamId)->with(['group', 'tags', 'owner']);

        $number = trim((string) ($request->input('number') ?? $request->input('q')));
        $minSearchLength = 3;
        if ($isClerk && strlen($number) < $minSearchLength) {
            $number = '';
        }

        if ($hasAdvancedSearch) {
            if ($q = trim((string) $request->input('q'))) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('company', 'like', "%{$q}%")
                        ->orWhere('city', 'like', "%{$q}%")
                        ->orWhere('area', 'like', "%{$q}%");
                });
            }
            if ($groupId = $request->input('group_id')) {
                $query->where('group_id', $groupId);
            }
            if ($tagIds = $request->input('tags')) {
                foreach ((array) $tagIds as $tagId) {
                    $query->whereHas('tags', fn ($t) => $t->where('tags.id', $tagId));
                }
            }
        }

        if ($number !== '') {
            $digits = Contact::normalizePhone($number);
            $query->where(function ($sub) use ($number, $digits) {
                if ($digits !== null) {
                    $sub->where('phone_digits', 'like', "%{$digits}%");
                }
                $sub->orWhere('phone', 'like', "%{$number}%")->orWhere('number', 'like', "%{$number}%");
            });
        } elseif ($isClerk) {
            // Clerks have search-only access — no query means no results.
            $query->whereRaw('1 = 0');
        }

        $this->scopeVisibleContactsForList($query, $user);

        $contacts = $query->orderBy('name')->paginate($request->integer('per_page', 25))->withQueryString();

        if (($isClerk || $user->isManager()) && $number !== '') {
            $user->increment('searches_used');
        }

        return JsonResource::collection($contacts);
    }

    public function show(Contact $contact): JsonResource
    {
        Gate::authorize('view', $contact);
        $this->ensureSameTeam($contact);

        return new JsonResource($contact->load([
            'group', 'tags', 'owner', 'approvedBy',
            'contactNotes' => fn ($q) => $q->latest(),
            'contactNotes.author',
            'editRequests' => fn ($q) => $q->where('status', 'pending'),
        ]));
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        Gate::authorize('create', Contact::class);

        $data = $request->validated();
        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);

        if (! empty($data['phone'])) {
            $exists = Contact::where('team_id', $request->user()->current_team_id)
                ->where('phone', $data['phone'])
                ->exists();
            if ($exists) {
                throw ValidationException::withMessages([
                    'phone' => 'This phone number already exists in your contacts.',
                ]);
            }
        }

        // Manager-created contacts require Admin+ approval before becoming active.
        $approvalStatus = $request->user()->isManager() ? 'pending' : 'approved';

        $data['owner_id'] = $request->user()->id;
        $data['team_id'] = $request->user()->current_team_id;
        $data['approval_status'] = $approvalStatus;
        $data['approved_by'] = $approvalStatus === 'approved' ? $request->user()->id : null;
        $data['approved_at'] = $approvalStatus === 'approved' ? now() : null;

        $contact = Contact::create($data);
        $contact->tags()->sync($tagIds);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store("contacts/{$contact->id}", 'public');
            $contact->update(['photo' => $path]);
        }

        ActivityLogger::log('contact.created', $contact, ['name' => $contact->name, 'approval' => $approvalStatus]);

        return response()->json($contact->fresh(['group', 'tags']), 201);
    }

    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        // UpdateContactRequest::authorize() already restricts this to Manager+/Admin+/Super Admin.
        // Clerks add content via POST /contacts/{contact}/notes instead.
        $this->ensureSameTeam($contact);

        $data = $request->validated();
        $tagIds = $data['tags'] ?? null;
        unset($data['tags']);

        if (! $request->user()->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN)) {
            return $this->queueEditRequest($request, $contact, $data, $tagIds);
        }

        $contact->update($data);

        if (is_array($tagIds)) {
            $contact->tags()->sync($tagIds);
        }

        if ($request->hasFile('photo')) {
            if ($contact->photo) {
                Storage::disk('public')->delete($contact->photo);
            }
            $path = $request->file('photo')->store("contacts/{$contact->id}", 'public');
            $contact->update(['photo' => $path]);
        }

        ActivityLogger::log('contact.updated', $contact, ['name' => $contact->name]);

        return response()->json(new JsonResource($contact->fresh(['group', 'tags'])));
    }

    /** Manager proposes changes; nothing applies until Admin/Super Admin approves via the approvals endpoints. */
    private function queueEditRequest(Request $request, Contact $contact, array $data, ?array $tagIds): JsonResponse
    {
        if ($contact->editRequests()->where('status', 'pending')->exists()) {
            throw ValidationException::withMessages([
                'edit' => 'This contact already has an edit awaiting approval.',
            ]);
        }

        $changes = [];
        $original = [];
        foreach ($data as $field => $value) {
            $old = $contact->$field;
            if ((string) ($old ?? '') !== (string) ($value ?? '')) {
                $changes[$field] = $value;
                $original[$field] = $old;
            }
        }

        $newTagIds = collect($tagIds ?? [])->map(fn ($id) => (int) $id)->sort()->values()->all();
        $currentTagIds = $contact->tags()->pluck('tags.id')->sort()->values()->all();
        $tagsChanged = $tagIds !== null && $newTagIds !== $currentTagIds;

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('pending-edits', 'local');
        }

        if (empty($changes) && ! $tagsChanged && ! $photoPath) {
            return response()->json(['message' => 'No changes to submit.']);
        }

        $editRequest = ContactEditRequest::create([
            'contact_id' => $contact->id,
            'team_id' => $contact->team_id,
            'requested_by' => $request->user()->id,
            'status' => 'pending',
            'changes' => $changes,
            'original' => $original,
            'tags' => $tagsChanged ? $newTagIds : null,
            'photo_path' => $photoPath,
        ]);

        ActivityLogger::log('contact.edit_requested', $contact, ['name' => $contact->name]);

        return response()->json([
            'message' => 'Your changes have been submitted for approval.',
            'edit_request' => $editRequest,
        ], 201);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        Gate::authorize('delete', $contact);
        $this->ensureSameTeam($contact);

        $name = $contact->name;
        ActivityLogger::log('contact.trashed', $contact, ['name' => $name]);
        $contact->delete();

        return response()->json(['deleted' => true]);
    }

    public function suspend(Contact $contact): JsonResponse
    {
        Gate::authorize('manage', $contact);
        $contact->update(['status' => 'suspended', 'suspended_at' => now()]);

        return response()->json(new JsonResource($contact->fresh()));
    }

    public function ban(Contact $contact): JsonResponse
    {
        Gate::authorize('manage', $contact);
        $contact->update(['status' => 'banned', 'banned_at' => now()]);

        return response()->json(new JsonResource($contact->fresh()));
    }

    public function reactivate(Contact $contact): JsonResponse
    {
        // Manager can ban/suspend via manage(), but only Admin+ can undo it.
        Gate::authorize('reactivate', $contact);
        $contact->update(['status' => 'active']);

        return response()->json(new JsonResource($contact->fresh()));
    }

    public function rate(Request $request, Contact $contact): JsonResponse
    {
        Gate::authorize('rate', $contact);
        $request->validate(['rating' => ['required', 'numeric', 'min:0', 'max:5']]);
        $contact->update(['rating' => $request->input('rating')]);

        return response()->json(new JsonResource($contact->fresh()));
    }
}
