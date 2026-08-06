<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GroupsApiController extends Controller
{
    /** Super Admin bypasses team scoping; everyone else is scoped to their team. */
    private function ensureSameTeam(Group $group): void
    {
        if (Auth::user()->isSuperAdmin()) {
            return;
        }
        abort_unless($group->team_id === Auth::user()->current_team_id, 403);
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('manage-groups');

        $groups = Group::where('team_id', $request->user()->current_team_id)
            ->withCount('contacts')
            ->orderBy('name')
            ->get();

        return response()->json($groups);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('manage-groups');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:16'],
        ]);

        $group = Group::create([
            'team_id' => $request->user()->current_team_id,
            'name' => $data['name'],
            'color' => $data['color'] ?? '#a855f7',
        ]);

        return response()->json($group, 201);
    }

    public function update(Request $request, Group $group): JsonResponse
    {
        Gate::authorize('manage-groups');
        $this->ensureSameTeam($group);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:16'],
        ]);

        $group->update($data);

        return response()->json($group->fresh());
    }

    public function destroy(Group $group): JsonResponse
    {
        Gate::authorize('manage-groups');
        $this->ensureSameTeam($group);

        // Null out group_id on contacts that belong to this group.
        $group->contacts()->update(['group_id' => null]);
        $group->delete();

        return response()->json(['deleted' => true]);
    }
}
