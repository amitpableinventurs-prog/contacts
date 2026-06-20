<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ActivityLogsController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-audit');

        // Only Super Admin sees all activity. Admin + Manager see Clerk + Manager actions only.
        $seesAll = Auth::user()->isSuperAdmin();

        $allowedUserIds = null;
        if (! $seesAll) {
            $allowedUserIds = User::whereIn('role', [Roles::CLERK, Roles::MANAGER])
                ->pluck('id');
        }

        $query = ActivityLog::with('user')->latest();

        if ($allowedUserIds !== null) {
            $query->whereIn('user_id', $allowedUserIds);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs = $query->paginate(50)->withQueryString();

        // User dropdown: Super Admin sees all; others see clerk+manager only
        $userQuery = User::orderBy('name');
        if (! $seesAll) {
            $userQuery->whereIn('role', [Roles::CLERK, Roles::MANAGER]);
        }
        $users   = $userQuery->get(['id', 'name']);
        $actions = ActivityLog::when($allowedUserIds, fn ($q) => $q->whereIn('user_id', $allowedUserIds))
            ->select('action')->distinct()->orderBy('action')->pluck('action');

        $isAdminPlus = $seesAll; // controls the "scoped view" banner

        return view('activity-logs.index', compact('logs', 'users', 'actions', 'isAdminPlus'));
    }
}
