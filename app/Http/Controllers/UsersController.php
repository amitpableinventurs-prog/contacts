<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ContactNote;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Roles the current user is allowed to assign.
     * Super Admin: any role. Admin: Manager + Clerk. Manager: Clerk only.
     */
    private function allowedRoles(): array
    {
        $actor = Auth::user();
        if ($actor->isSuperAdmin()) return Roles::ALL;
        if ($actor->isAdmin())      return [Roles::MANAGER, Roles::CLERK];
        return [Roles::CLERK]; // Manager can only create Clerks
    }

    /**
     * Super Admin can act on anyone.
     * Admin can only act on Manager and Clerk accounts.
     * Manager can only act on Clerk accounts.
     */
    private function canActOn(User $target): bool
    {
        $actor = Auth::user();
        if ($actor->isSuperAdmin()) return true;
        if ($actor->isAdmin())      return $target->hasRole(Roles::MANAGER, Roles::CLERK);
        return $target->hasRole(Roles::CLERK); // Manager only manages Clerks
    }

    public function index(): View
    {
        Gate::authorize('manage-users');

        $actor = Auth::user();
        $query = User::orderBy('name');

        if ($actor->isManager()) {
            $query->where('role', Roles::CLERK);
        } elseif (! $actor->isSuperAdmin()) {
            $query->whereIn('role', [Roles::MANAGER, Roles::CLERK]);
        }

        $users = $query->paginate(25);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        Gate::authorize('manage-users');

        return view('users.create', ['roles' => $this->allowedRoles()]);
    }

    /** Profile page: details, search-quota usage, and recent activity. */
    public function show(User $user): View
    {
        Gate::authorize('manage-users');
        abort_unless($user->id === Auth::id() || $this->canActOn($user), 403);

        $recentLogs = ActivityLog::where('user_id', $user->id)->latest()->limit(20)->get();
        $logCount   = ActivityLog::where('user_id', $user->id)->count();
        $loginCount = ActivityLog::where('user_id', $user->id)
            ->where('action', 'like', '%login%')->count();
        $noteCount  = ContactNote::where('user_id', $user->id)->count();

        return view('users.show', compact('user', 'recentLogs', 'logCount', 'loginCount', 'noteCount') + [
            'canEdit' => $this->canActOn($user),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-users');

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'role'         => ['required', 'in:'.implode(',', $this->allowedRoles())],
            'search_quota' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($data['role'] === Roles::SUPER_ADMIN && User::where('role', Roles::SUPER_ADMIN)->exists()) {
            return back()->withInput()->withErrors(['role' => 'A Super Admin already exists — only one is allowed.']);
        }

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'role'         => $data['role'],
            'is_active'    => true,
            'search_quota' => (int) ($data['search_quota'] ?? 0),
            'searches_used'=> 0,
        ]);

        // Join the creator's workspace so the new account shares the same
        // contacts/groups/tags instead of landing in its own empty team.
        $creatorTeamId = Auth::user()->current_team_id;
        if ($creatorTeamId) {
            $user->teams()->syncWithoutDetaching([$creatorTeamId => ['role' => 'member']]);
            $user->forceFill(['current_team_id' => $creatorTeamId])->save();
        }

        ActivityLogger::log('user.created', $user, ['name' => $user->name, 'role' => $user->role]);

        return redirect()->route('users.index')
            ->with('toast', ['type' => 'success', 'message' => "{$user->name} created."]);
    }

    public function edit(User $user): View
    {
        Gate::authorize('manage-users');
        abort_unless($this->canActOn($user), 403, 'You cannot edit a Super Admin account.');

        return view('users.edit', ['user' => $user, 'roles' => $this->allowedRoles()]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('manage-users');
        abort_unless($this->canActOn($user), 403, 'You cannot edit a Super Admin account.');

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role'         => ['required', 'in:'.implode(',', $this->allowedRoles())],
            'is_active'    => ['nullable', 'boolean'],
            'search_quota' => ['nullable', 'integer', 'min:0'],
            'reset_searches' => ['nullable', 'boolean'],
        ]);

        if ($data['role'] === Roles::SUPER_ADMIN && $user->role !== Roles::SUPER_ADMIN
            && User::where('role', Roles::SUPER_ADMIN)->exists()) {
            return back()->withInput()->withErrors(['role' => 'A Super Admin already exists — only one is allowed.']);
        }

        $updateData = [
            'name'         => $data['name'],
            'email'        => $data['email'],
            'role'         => $data['role'],
            'is_active'    => (bool) ($data['is_active'] ?? false),
            'search_quota' => (int) ($data['search_quota'] ?? 0),
        ];

        if (!empty($data['reset_searches'])) {
            $updateData['searches_used'] = 0;
        }

        $user->update($updateData);

        ActivityLogger::log('user.updated', $user, ['name' => $user->name, 'role' => $user->role]);

        return redirect()->route('users.index')
            ->with('toast', ['type' => 'success', 'message' => "{$user->name} updated."]);
    }

    public function changePassword(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('manage-users');
        abort_unless($this->canActOn($user), 403, 'You cannot change a Super Admin password.');

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($request->input('password'))]);
        ActivityLogger::log('user.password_changed', $user, ['name' => $user->name]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Password changed.']);
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('manage-users');

        if ($user->id === Auth::id()) {
            return back()->with('toast', ['type' => 'error', 'message' => 'You cannot delete your own account.']);
        }

        abort_unless($this->canActOn($user), 403, 'You cannot delete a Super Admin account.');

        ActivityLogger::log('user.deleted', null, ['name' => $user->name, 'email' => $user->email]);
        $user->delete();

        return redirect()->route('users.index')
            ->with('toast', ['type' => 'success', 'message' => 'User deleted.']);
    }
}
