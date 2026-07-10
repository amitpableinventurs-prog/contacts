<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogsController extends Controller
{
    /**
     * Logs the current user may see, with request filters applied.
     * Only Super Admin sees all activity; Admin + Manager see Clerk +
     * Manager actions only. No ordering — callers add their own.
     */
    protected function filteredQuery(Request $request)
    {
        $query = ActivityLog::query();

        if (! Auth::user()->isSuperAdmin()) {
            $query->whereIn('user_id', User::whereIn('role', [Roles::CLERK, Roles::MANAGER])->pluck('id'));
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

        return $query;
    }

    public function index(Request $request): View
    {
        Gate::authorize('view-audit');

        $seesAll = Auth::user()->isSuperAdmin();

        $allowedUserIds = $seesAll
            ? null
            : User::whereIn('role', [Roles::CLERK, Roles::MANAGER])->pluck('id');

        $logs = $this->filteredQuery($request)->with('user')->latest()
            ->paginate(50)->withQueryString();

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

    /** Stream the currently filtered logs as a CSV download. */
    public function export(Request $request): StreamedResponse
    {
        Gate::authorize('view-audit');

        $query = $this->filteredQuery($request)->with('user');

        return response()->streamDownload(function () use ($query) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Date', 'Time', 'User', 'Email', 'Action', 'Entity type', 'Entity ID', 'IP', 'Browser', 'Device', 'Other details']);

            $query->orderBy('id')->chunk(1000, function ($logs) use ($h) {
                foreach ($logs as $log) {
                    $meta  = $log->metadata ?? [];
                    $other = array_diff_key($meta, array_flip(['ip', 'browser', 'device']));
                    fputcsv($h, [
                        $log->created_at?->format('Y-m-d'),
                        $log->created_at?->format('H:i:s'),
                        $log->user?->name ?? 'System',
                        $log->user?->email ?? '',
                        $log->action,
                        $log->entity_type,
                        $log->entity_id,
                        $meta['ip'] ?? '',
                        $meta['browser'] ?? '',
                        $meta['device'] ?? '',
                        $other ? json_encode($other) : '',
                    ]);
                }
            });

            fclose($h);
        }, 'activity-logs-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    /** Super Admin only: delete the selected log entries. */
    public function destroySelected(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403, 'Only Super Admin can delete logs.');

        $data = $request->validate([
            'log_ids'   => ['required', 'array', 'min:1'],
            'log_ids.*' => ['integer'],
        ]);

        $count = ActivityLog::whereIn('id', $data['log_ids'])->delete();

        return back()->with('toast', ['type' => 'success', 'message' => "{$count} log ".\Illuminate\Support\Str::plural('entry', $count).' deleted.']);
    }
}
