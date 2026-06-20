<?php

namespace App\Http\Controllers;

use App\Models\IpLoginLog;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LoginLogsController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-login-logs');

        // Only Super Admin sees all logins. Admin + Manager see Clerk + Manager logins only.
        $seesAll = Auth::user()->isSuperAdmin();

        $allowedUserIds = null;
        if (! $seesAll) {
            $allowedUserIds = User::whereIn('role', [Roles::CLERK, Roles::MANAGER])
                ->pluck('id');
        }

        $query = IpLoginLog::with('user')->latest();

        if ($allowedUserIds !== null) {
            $query->whereIn('user_id', $allowedUserIds);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($ip = $request->input('ip')) {
            $query->where('ip_address', 'like', "%{$ip}%");
        }
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs = $query->paginate(40)->withQueryString();

        $userQuery = User::orderBy('name');
        if (! $seesAll) {
            $userQuery->whereIn('role', [Roles::CLERK, Roles::MANAGER]);
        }
        $users = $userQuery->get(['id', 'name', 'email']);

        $isAdminPlus = $seesAll; // controls the "scoped view" banner

        return view('login-logs.index', compact('logs', 'users', 'isAdminPlus'));
    }
}
