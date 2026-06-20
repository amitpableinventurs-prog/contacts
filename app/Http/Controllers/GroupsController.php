<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GroupsController extends Controller
{
    /** Super Admin bypasses team scoping; everyone else is scoped to their team. */
    private function ensureSameTeam(Group $group): void
    {
        if (Auth::user()->isSuperAdmin()) {
            return;
        }
        abort_unless($group->team_id === Auth::user()->current_team_id, 403);
    }

    public function index(): View
    {
        Gate::authorize('manage-groups');

        $groups = Group::where('team_id', Auth::user()->current_team_id)
            ->withCount('contacts')
            ->orderBy('name')
            ->get();

        return view('groups.index', compact('groups'));
    }

    public function create(): View
    {
        Gate::authorize('manage-groups');

        return view('groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-groups');

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:16'],
        ]);

        Group::create([
            'team_id' => Auth::user()->current_team_id,
            'name'    => $data['name'],
            'color'   => $data['color'] ?? '#a855f7',
        ]);

        return redirect()->route('groups.index')
            ->with('toast', ['type' => 'success', 'message' => "Group \"{$data['name']}\" created."]);
    }

    public function edit(Group $group): View
    {
        Gate::authorize('manage-groups');
        $this->ensureSameTeam($group);

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        Gate::authorize('manage-groups');
        $this->ensureSameTeam($group);

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:16'],
        ]);

        $group->update($data);

        return redirect()->route('groups.index')
            ->with('toast', ['type' => 'success', 'message' => 'Group updated.']);
    }

    public function destroy(Group $group): RedirectResponse
    {
        Gate::authorize('manage-groups');
        $this->ensureSameTeam($group);

        // Null out group_id on contacts that belong to this group
        $group->contacts()->update(['group_id' => null]);
        $group->delete();

        return redirect()->route('groups.index')
            ->with('toast', ['type' => 'success', 'message' => 'Group deleted. Contacts moved to no-group.']);
    }
}
