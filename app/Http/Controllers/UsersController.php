<?php

namespace App\Http\Controllers;

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
     * Super Admin: any role. Admin: only Manager and Clerk (cannot create admins).
     */
    private function allowedRoles(): array
    {
        return Auth::user()->isSuperAdmin()
            ? Roles::ALL
            : [Roles::MANAGER, Roles::CLERK];
    }

    /**
     * Super Admin can act on anyone.
     * Admin can only act on Manager and Clerk accounts
     * (cannot edit/delete other Admins or Super Admins).
     */
    private function canActOn(User $target): bool
    {
        if (Auth::user()->isSuperAdmin()) return true;

        return $target->hasRole(Roles::MANAGER, Roles::CLERK);
    }

    public function index(): View
    {
        Gate::authorize('manage-users');

        // Super Admin sees everyone. Admin sees only Manager + Clerk accounts.
        $query = User::orderBy('name');
        if (! Auth::user()->isSuperAdmin()) {
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

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-users');

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:'.implode(',', $this->allowedRoles())],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'is_active' => true,
        ]);

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
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role'      => ['required', 'in:'.implode(',', $this->allowedRoles())],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

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
