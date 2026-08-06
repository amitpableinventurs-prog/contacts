<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsersApiController extends Controller
{
    /** Roles the current user is allowed to assign. Super Admin: any. Admin: Manager+Clerk. Manager: Clerk only. */
    private function allowedRoles(): array
    {
        $actor = Auth::user();
        if ($actor->isSuperAdmin()) {
            return Roles::ALL;
        }
        if ($actor->isAdmin()) {
            return [Roles::MANAGER, Roles::CLERK];
        }

        return [Roles::CLERK];
    }

    /** Super Admin can act on anyone. Admin: Manager/Clerk only. Manager: Clerk only. */
    private function canActOn(User $target): bool
    {
        $actor = Auth::user();
        if ($actor->isSuperAdmin()) {
            return true;
        }
        if ($actor->isAdmin()) {
            return $target->hasRole(Roles::MANAGER, Roles::CLERK);
        }

        return $target->hasRole(Roles::CLERK);
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('manage-users');

        $actor = $request->user();
        $query = User::orderBy('name');

        if ($actor->isManager()) {
            $query->where('role', Roles::CLERK);
        } elseif (! $actor->isSuperAdmin()) {
            $query->whereIn('role', [Roles::MANAGER, Roles::CLERK]);
        }

        return response()->json($query->paginate($request->integer('per_page', 25)));
    }

    public function show(User $user): JsonResponse
    {
        Gate::authorize('manage-users');
        abort_unless($user->id === Auth::id() || $this->canActOn($user), 403);

        return response()->json($user);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('manage-users');
        abort_unless(Auth::user()->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN), 403, 'Only Admin and above can add users.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:'.implode(',', $this->allowedRoles())],
            'search_quota' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($data['role'] === Roles::SUPER_ADMIN && User::where('role', Roles::SUPER_ADMIN)->exists()) {
            return response()->json([
                'message' => 'A Super Admin already exists — only one is allowed.',
                'errors' => ['role' => ['A Super Admin already exists — only one is allowed.']],
            ], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => true,
            'search_quota' => (int) ($data['search_quota'] ?? 0),
            'searches_used' => 0,
        ]);

        // Join the creator's workspace so the new account shares the same contacts/groups/tags.
        $creatorTeamId = Auth::user()->current_team_id;
        if ($creatorTeamId) {
            $user->teams()->syncWithoutDetaching([$creatorTeamId => ['role' => 'member']]);
            $user->forceFill(['current_team_id' => $creatorTeamId])->save();
        }

        ActivityLogger::log('user.created', $user, ['name' => $user->name, 'role' => $user->role]);

        return response()->json($user->fresh(), 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        Gate::authorize('manage-users');
        abort_unless($this->canActOn($user), 403, 'You cannot edit a Super Admin account.');
        abort_if($user->isLocked(), 403, 'This user is locked. Unlock it first to make changes.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:'.implode(',', $this->allowedRoles())],
            'is_active' => ['nullable', 'boolean'],
            'search_quota' => ['nullable', 'integer', 'min:0'],
            'reset_searches' => ['nullable', 'boolean'],
        ]);

        if ($data['role'] === Roles::SUPER_ADMIN && $user->role !== Roles::SUPER_ADMIN
            && User::where('role', Roles::SUPER_ADMIN)->exists()) {
            return response()->json([
                'message' => 'A Super Admin already exists — only one is allowed.',
                'errors' => ['role' => ['A Super Admin already exists — only one is allowed.']],
            ], 422);
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => (bool) ($data['is_active'] ?? false),
            'search_quota' => (int) ($data['search_quota'] ?? 0),
        ];

        if (! empty($data['reset_searches'])) {
            $updateData['searches_used'] = 0;
        }

        $user->update($updateData);

        ActivityLogger::log('user.updated', $user, ['name' => $user->name, 'role' => $user->role]);

        return response()->json($user->fresh());
    }

    public function changePassword(Request $request, User $user): JsonResponse
    {
        Gate::authorize('manage-users');
        abort_unless($this->canActOn($user), 403, 'You cannot change a Super Admin password.');
        abort_if($user->isLocked(), 403, 'This user is locked. Unlock it first to make changes.');

        $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed']]);

        $user->update(['password' => Hash::make($request->input('password'))]);
        ActivityLogger::log('user.password_changed', $user, ['name' => $user->name]);

        return response()->json(['updated' => true]);
    }

    public function destroy(User $user): JsonResponse
    {
        Gate::authorize('manage-users');

        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422);
        }

        abort_unless($this->canActOn($user), 403, 'You cannot delete a Super Admin account.');
        abort_if($user->isLocked(), 403, 'This user is locked. Unlock it first to delete it.');

        ActivityLogger::log('user.deleted', null, ['name' => $user->name, 'email' => $user->email]);
        $user->delete();

        return response()->json(['deleted' => true]);
    }

    public function lock(User $user): JsonResponse
    {
        abort_unless(Auth::user()->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN), 403, 'Only Admin and above can lock users.');
        abort_unless($this->canActOn($user), 403);
        abort_if($user->id === Auth::id(), 403, 'You cannot lock your own account.');

        $user->forceFill(['locked_at' => now(), 'locked_by' => Auth::id()])->save();
        ActivityLogger::log('user.locked', null, ['name' => $user->name]);

        return response()->json(['locked' => true]);
    }

    public function unlock(User $user): JsonResponse
    {
        abort_unless(Auth::user()->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN), 403, 'Only Admin and above can unlock users.');
        abort_unless($this->canActOn($user), 403);

        $user->forceFill(['locked_at' => null, 'locked_by' => null])->save();
        ActivityLogger::log('user.unlocked', null, ['name' => $user->name]);

        return response()->json(['unlocked' => true]);
    }
}
