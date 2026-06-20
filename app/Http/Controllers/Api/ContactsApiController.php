<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ContactsApiController extends Controller
{
    public function index(Request $request): JsonResource
    {
        Gate::authorize('viewAny', Contact::class);

        $query = Contact::query()->with(['group', 'tags']);

        if ($q = $request->string('q')->toString()) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        return JsonResource::collection($query->paginate($request->integer('per_page', 25)));
    }

    public function show(Contact $contact): JsonResource
    {
        Gate::authorize('view', $contact);

        return new JsonResource($contact->load(['group', 'tags', 'owner']));
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $data = $request->validated();
        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);
        $data['owner_id'] = $request->user()->id;

        $contact = Contact::create($data);
        $contact->tags()->sync($tagIds);

        return response()->json($contact->fresh(['group', 'tags']), 201);
    }

    public function update(UpdateContactRequest $request, Contact $contact): JsonResource
    {
        $data = $request->validated();
        $tagIds = $data['tags'] ?? null;
        unset($data['tags']);

        $contact->update($data);
        if (is_array($tagIds)) {
            $contact->tags()->sync($tagIds);
        }

        return new JsonResource($contact->fresh(['group', 'tags']));
    }

    public function destroy(Contact $contact): JsonResponse
    {
        Gate::authorize('delete', $contact);
        $contact->delete();

        return response()->json(['deleted' => true]);
    }
}
