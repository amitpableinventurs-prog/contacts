<?php

namespace App\Http\Controllers;

use App\Models\SearchLog;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Manager sees the Clerks on their own team; Admin sees every
     * Manager and Clerk system-wide (matching the existing scope used
     * for activity/login logs).
     */
    public function index(): View
    {
        $actor = Auth::user();
        abort_unless($actor->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER), 403);

        if ($actor->isManager()) {
            $members = User::where('current_team_id', $actor->current_team_id)
                ->where('role', Roles::CLERK)
                ->orderBy('name')
                ->get();
        } else {
            $members = User::whereIn('role', [Roles::MANAGER, Roles::CLERK])
                ->orderBy('role')
                ->orderBy('name')
                ->get();
        }

        return view('team.index', compact('members'));
    }

    /** Recent phone-number searches performed by a team member. */
    public function searchHistory(User $user): View
    {
        $actor = Auth::user();
        abort_unless($actor->hasRole(Roles::SUPER_ADMIN, Roles::ADMIN, Roles::MANAGER), 403);

        if ($actor->isManager()) {
            abort_unless(
                $user->role === Roles::CLERK && $user->current_team_id === $actor->current_team_id,
                403
            );
        } else {
            abort_unless(in_array($user->role, [Roles::MANAGER, Roles::CLERK], true), 403);
        }

        $logs = SearchLog::where('user_id', $user->id)->latest()->paginate(30);

        return view('team.search-history', compact('user', 'logs'));
    }
}
