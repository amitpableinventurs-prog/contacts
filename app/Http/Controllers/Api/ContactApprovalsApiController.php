<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactEditRequest;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ContactApprovalsApiController extends Controller
{
    private function ensureSameTeam(Contact $contact): void
    {
        if (Auth::user()->isSuperAdmin()) {
            return;
        }
        abort_unless($contact->team_id === Auth::user()->current_team_id, 403);
    }

    public function pending(Request $request): JsonResponse
    {
        Gate::authorize('approve-contacts');

        $contacts = Contact::where('approval_status', 'pending')
            ->when(! $request->user()->isSuperAdmin(), fn ($q) => $q->where('team_id', $request->user()->current_team_id))
            ->with(['owner', 'group', 'tags'])
            ->latest()
            ->paginate(25, ['*'], 'contacts_page');

        $editRequests = ContactEditRequest::where('status', 'pending')
            ->when(! $request->user()->isSuperAdmin(), fn ($q) => $q->where('team_id', $request->user()->current_team_id))
            ->with(['contact', 'requestedBy'])
            ->latest()
            ->paginate(25, ['*'], 'edits_page');

        return response()->json([
            'contacts' => $contacts,
            'edit_requests' => $editRequests,
        ]);
    }

    public function approve(Contact $contact): JsonResponse
    {
        Gate::authorize('approve-contacts');
        $this->ensureSameTeam($contact);

        $contact->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        ActivityLogger::log('contact.approved', $contact, ['name' => $contact->name]);

        return response()->json(new JsonResource($contact->fresh()));
    }

    public function reject(Contact $contact): JsonResponse
    {
        Gate::authorize('approve-contacts');
        $this->ensureSameTeam($contact);

        $contact->update(['approval_status' => 'rejected']);
        ActivityLogger::log('contact.rejected', $contact, ['name' => $contact->name]);

        return response()->json(new JsonResource($contact->fresh()));
    }

    public function approveEdit(ContactEditRequest $editRequest): JsonResponse
    {
        Gate::authorize('approve-edits');

        $contact = $editRequest->contact;
        $this->ensureSameTeam($contact);
        abort_unless($editRequest->status === 'pending', 404);

        $contact->update($editRequest->changes);

        if ($editRequest->tags !== null) {
            $contact->tags()->sync($editRequest->tags);
        }

        if ($editRequest->photo_path) {
            if ($contact->photo) {
                Storage::disk('public')->delete($contact->photo);
            }
            $newPath = "contacts/{$contact->id}/".basename($editRequest->photo_path);
            Storage::disk('public')->put($newPath, Storage::disk('local')->get($editRequest->photo_path));
            Storage::disk('local')->delete($editRequest->photo_path);
            $contact->update(['photo' => $newPath]);
        }

        $editRequest->update(['status' => 'approved', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);

        ActivityLogger::log('contact.edit_approved', $contact, ['name' => $contact->name]);

        return response()->json([
            'approved' => true,
            'contact' => new JsonResource($contact->fresh(['group', 'tags'])),
        ]);
    }

    public function rejectEdit(ContactEditRequest $editRequest): JsonResponse
    {
        Gate::authorize('approve-edits');

        $contact = $editRequest->contact;
        $this->ensureSameTeam($contact);
        abort_unless($editRequest->status === 'pending', 404);

        if ($editRequest->photo_path) {
            Storage::disk('local')->delete($editRequest->photo_path);
        }

        $editRequest->update(['status' => 'rejected', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);

        ActivityLogger::log('contact.edit_rejected', $contact, ['name' => $contact->name]);

        return response()->json(['rejected' => true]);
    }
}
