<x-app-layout>
    <x-slot:header>Admin / Activity Logs</x-slot:header>

    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Activity Logs</h1>
            <p class="text-sm text-muted-foreground">Full audit trail of every action performed in the system.</p>
        </div>

        @if (!($isAdminPlus ?? true))
            <div class="rounded-md border border-blue-200 bg-blue-50 px-4 py-2 text-sm text-blue-800">
                Showing activities for <strong>Clerk</strong> and <strong>Manager</strong> accounts only.
            </div>
        @endif

        {{-- Filters --}}
        <x-ui.card>
            <x-ui.card-content class="p-4">
                <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-2 sm:flex sm:flex-wrap items-end gap-3">
                    <div class="space-y-1 col-span-2 sm:col-auto">
                        <x-ui.label class="text-xs">User</x-ui.label>
                        <select name="user_id" class="flex h-9 w-full sm:w-44 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                            <option value="">All users</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1 col-span-2 sm:col-auto">
                        <x-ui.label class="text-xs">Action</x-ui.label>
                        <select name="action" class="flex h-9 w-full sm:w-44 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                            <option value="">All actions</option>
                            @foreach ($actions as $a)
                                <option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <x-ui.label class="text-xs">From date</x-ui.label>
                        <x-ui.input name="from" type="date" value="{{ request('from') }}" class="w-full sm:w-36" />
                    </div>
                    <div class="space-y-1">
                        <x-ui.label class="text-xs">To date</x-ui.label>
                        <x-ui.input name="to" type="date" value="{{ request('to') }}" class="w-full sm:w-36" />
                    </div>
                    <x-ui.button type="submit" variant="secondary">Filter</x-ui.button>
                    @if (request()->hasAny(['user_id','action','from','to']))
                        <a href="{{ route('activity-logs.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Clear</a>
                    @endif
                </form>
            </x-ui.card-content>
        </x-ui.card>

        @php $isSuperAdmin = auth()->user()->isSuperAdmin(); @endphp

        <form method="POST" action="{{ route('activity-logs.delete') }}"
              onsubmit="return confirm('Delete selected log entries? This cannot be undone.')"
              x-data="{ checked: 0 }"
              @change="checked = $el.querySelectorAll('input[data-log-cb]:checked').length"
              class="space-y-4">
            @csrf

            {{-- Stats / actions bar --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm text-muted-foreground">
                    {{ $logs->total() }} {{ \Illuminate\Support\Str::plural('record', $logs->total()) }} found
                </div>
                <div class="flex items-center gap-2">
                    @if ($isSuperAdmin)
                        <x-ui.button type="submit" variant="destructive" size="sm" x-show="checked > 0" x-cloak>
                            Delete selected (<span x-text="checked"></span>)
                        </x-ui.button>
                    @endif
                    <a href="{{ route('activity-logs.export', request()->query()) }}">
                        <x-ui.button type="button" variant="outline" size="sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Export CSV
                        </x-ui.button>
                    </a>
                </div>
            </div>

        {{-- Table --}}
        <x-ui.card class="overflow-hidden">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row class="hover:bg-transparent">
                        @if ($isSuperAdmin)
                            <x-ui.table-head class="w-10">
                                <input type="checkbox" class="rounded border-input"
                                       @change="document.querySelectorAll('input[data-log-cb]').forEach(cb => cb.checked = $event.target.checked); checked = $event.target.checked ? document.querySelectorAll('input[data-log-cb]').length : 0" />
                            </x-ui.table-head>
                        @endif
                        <x-ui.table-head>User</x-ui.table-head>
                        <x-ui.table-head>Action</x-ui.table-head>
                        <x-ui.table-head>Entity</x-ui.table-head>
                        <x-ui.table-head>Details</x-ui.table-head>
                        <x-ui.table-head>When</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($logs as $log)
                        @php
                            $actionColor = match(true) {
                                str_contains($log->action, 'created') => 'bg-green-100 text-green-800',
                                str_contains($log->action, 'updated') => 'bg-blue-100 text-blue-800',
                                str_contains($log->action, 'deleted')
                                    || str_contains($log->action, 'trashed') => 'bg-red-100 text-red-800',
                                str_contains($log->action, 'login')   => 'bg-purple-100 text-purple-800',
                                str_contains($log->action, 'logout')  => 'bg-gray-100 text-gray-800',
                                default => 'bg-yellow-100 text-yellow-800',
                            };
                            $actionLabel = ucwords(str_replace(['.', '_'], ' ', $log->action));
                        @endphp
                        @php
                            $viewer = auth()->user();
                            $canViewUser = $log->user && (
                                $viewer->isSuperAdmin()
                                || ($viewer->isAdmin() && in_array($log->user->role, ['manager', 'clerk'], true))
                                || ($viewer->isManager() && $log->user->role === 'clerk')
                            );
                        @endphp
                        <x-ui.table-row>
                            @if ($isSuperAdmin)
                                <x-ui.table-cell>
                                    <input type="checkbox" data-log-cb name="log_ids[]" value="{{ $log->id }}" class="rounded border-input" />
                                </x-ui.table-cell>
                            @endif
                            <x-ui.table-cell>
                                @if ($canViewUser)
                                    <a href="{{ route('users.show', $log->user) }}" class="flex items-center gap-2 group">
                                        <x-ui.avatar :name="$log->user->name" size="xs" />
                                        <div>
                                            <div class="text-sm font-medium group-hover:underline">{{ $log->user->name }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $log->user->email }}</div>
                                        </div>
                                    </a>
                                @else
                                    <div class="flex items-center gap-2">
                                        <x-ui.avatar :name="$log->user?->name ?? 'System'" size="xs" />
                                        <div>
                                            <div class="text-sm font-medium">{{ $log->user?->name ?? 'System' }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $log->user?->email }}</div>
                                        </div>
                                    </div>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $actionColor }}">
                                    {{ $actionLabel }}
                                </span>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-sm">
                                @if ($log->entity_type)
                                    <span class="text-muted-foreground">{{ $log->entity_type }}</span>
                                    @if ($log->entity_id)
                                        <span class="font-mono text-xs text-muted-foreground ml-1">#{{ $log->entity_id }}</span>
                                    @endif
                                @else
                                    <span class="text-muted-foreground">—</span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if ($log->metadata)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach (array_slice($log->metadata, 0, 3) as $key => $value)
                                            <span class="inline-flex items-center rounded border border-input px-1.5 py-0.5 text-[11px] text-muted-foreground">
                                                {{ $key }}: <span class="ml-1 font-medium text-foreground">{{ is_scalar($value) ? $value : '…' }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted-foreground">—</span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="text-sm">{{ $log->created_at->format('M j, Y') }}</div>
                                <div class="text-xs text-muted-foreground font-mono">{{ $log->created_at->format('H:i:s') }}</div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="{{ $isSuperAdmin ? 6 : 5 }}" class="text-center py-12 text-muted-foreground">
                                No activity recorded yet.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
            @if ($logs->hasPages())
                <div class="border-t p-3">{{ $logs->links() }}</div>
            @endif
        </x-ui.card>
        </form>
    </div>
</x-app-layout>
