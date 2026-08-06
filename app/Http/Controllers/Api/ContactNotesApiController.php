<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactNote;
use App\Support\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ContactNotesApiController extends Controller
{
    public function store(Request $request, Contact $contact): JsonResponse
    {
        Gate::authorize('addNote', $contact);

        $data = $request->validate(['note_html' => ['required', 'string', 'max:10000']]);

        $note = ContactNote::create([
            'team_id' => $contact->team_id,
            'contact_id' => $contact->id,
            'user_id' => $request->user()->id,
            'note_html' => $data['note_html'],
        ]);

        ActivityLogger::log('note.added', $contact, [
            'name' => $contact->name,
            'note' => Str::limit($data['note_html'], 100),
        ]);

        return response()->json($note->fresh('author'), 201);
    }

    public function update(Request $request, Contact $contact, ContactNote $note): JsonResponse
    {
        Gate::authorize('editNote', $note);
        abort_unless($note->contact_id === $contact->id, 403);

        $data = $request->validate(['note_html' => ['required', 'string', 'max:10000']]);
        $note->update(['note_html' => $data['note_html']]);

        ActivityLogger::log('note.updated', $contact, [
            'name' => $contact->name,
            'note' => Str::limit($data['note_html'], 100),
        ]);

        return response()->json($note->fresh('author'));
    }

    public function destroy(Contact $contact, ContactNote $note): JsonResponse
    {
        Gate::authorize('manage', $contact);
        abort_unless($note->contact_id === $contact->id, 403);

        $note->delete();

        ActivityLogger::log('note.deleted', $contact, ['name' => $contact->name]);

        return response()->json(['deleted' => true]);
    }
}
