<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TagsApiController extends Controller
{
    public function index(): JsonResponse
    {
        Gate::authorize('view-tags');

        $tags = Tag::withCount('contacts')->orderBy('name')->get();

        return response()->json($tags);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('manage-tags');

        $data = $request->validate(['name' => ['required', 'string', 'max:60']]);

        $teamId = $request->user()->current_team_id;
        $slug = Str::slug($data['name']);

        if (Tag::where('slug', $slug)->exists()) {
            return response()->json([
                'message' => 'A tag with that name already exists.',
                'errors' => ['name' => ['A tag with that name already exists.']],
            ], 422);
        }

        $tag = Tag::create([...$data, 'slug' => $slug, 'team_id' => $teamId]);

        return response()->json($tag, 201);
    }

    public function update(Request $request, Tag $tag): JsonResponse
    {
        Gate::authorize('manage-tags');

        $data = $request->validate(['name' => ['required', 'string', 'max:60']]);
        $newSlug = Str::slug($data['name']);

        if ($newSlug !== $tag->slug && Tag::where('slug', $newSlug)->where('id', '!=', $tag->id)->exists()) {
            return response()->json([
                'message' => 'A tag with that name already exists.',
                'errors' => ['name' => ['A tag with that name already exists.']],
            ], 422);
        }

        $tag->update([...$data, 'slug' => $newSlug]);

        return response()->json($tag->fresh());
    }

    public function destroy(Tag $tag): JsonResponse
    {
        Gate::authorize('manage-tags');
        $tag->delete();

        return response()->json(['deleted' => true]);
    }
}
