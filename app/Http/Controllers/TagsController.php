<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagsController extends Controller
{
    public function index(): View
    {
        Gate::authorize('view-tags');

        $tags = Tag::withCount('contacts')->orderBy('name')->get();

        return view('tags.index', compact('tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-tags');

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:60'],
            'color' => ['nullable', 'string', 'max:16'],
        ]);

        $teamId = auth()->user()->current_team_id;
        $slug   = Str::slug($data['name']);

        if (Tag::where('slug', $slug)->exists()) {
            return back()->withErrors(['name' => 'A tag with that name already exists.'])->withInput();
        }

        Tag::create([...$data, 'slug' => $slug, 'team_id' => $teamId]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Tag created.']);
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        Gate::authorize('manage-tags');

        $data    = $request->validate(['name' => ['required', 'string', 'max:60'], 'color' => ['nullable', 'string', 'max:16']]);
        $newSlug = Str::slug($data['name']);

        if ($newSlug !== $tag->slug && Tag::where('slug', $newSlug)->where('id', '!=', $tag->id)->exists()) {
            return back()->withErrors(['name' => 'A tag with that name already exists.'])->withInput();
        }

        $tag->update([...$data, 'slug' => $newSlug]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Tag updated.']);
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        Gate::authorize('manage-tags');
        $tag->delete();
        return back()->with('toast', ['type' => 'success', 'message' => 'Tag deleted.']);
    }
}
