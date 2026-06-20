<x-app-layout>
    <x-slot:header>Admin / Login Logs</x-slot:header>

    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">IP Login Logs</h1>
            <p class="text-sm text-muted-foreground">Track who logged in, from where, on which device, and when they logged out.</p>
        </div>

        @if (!($isAdminPlus ?? true))
            <div class="rounded-md border border-blue-200 bg-blue-50 px-4 py-2 text-sm text-blue-800">
                Showing login records for <strong>Clerk</strong> and <strong>Manager</strong> accounts only.
            </div>
        @endif

        {{-- Filters --}}
        <x-ui.card>
            <x-ui.card-content class="p-4">
                <form method="GET" action="{{ route('login-logs.index') }}" class="grid grid-cols-2 sm:flex sm:flex-wrap items-end gap-3">
                    <div class="space-y-1 col-span-2 sm:col-auto">
                        <x-ui.label class="text-xs">User</x-ui.label>
                        <select name="user_id" class="flex h-9 w-full sm:w-48 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                            <option value="">All users</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1 col-span-2 sm:col-auto">
                        <x-ui.label class="text-xs">IP address</x-ui.label>
                        <x-ui.input name="ip" value="{{ request('ip') }}" placeholder="192.168." class="w-full sm:w-36" />
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
                    @if (request()->hasAny(['user_id','ip','from','to']))
                        <a href="{{ route('login-logs.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Clear</a>
                    @endif
                </form>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Table --}}
        <x-ui.card class="overflow-hidden">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row class="hover:bg-transparent">
                        <x-ui.table-head>User</x-ui.table-head>
                        <x-ui.table-head>IP Address</x-ui.table-head>
                        <x-ui.table-head>Browser</x-ui.table-head>
                        <x-ui.table-head>Device</x-ui.table-head>
                        <x-ui.table-head>Platform</x-ui.table-head>
                        <x-ui.table-head>Login time</x-ui.table-head>
                        <x-ui.table-head>Logout time</x-ui.table-head>
                        <x-ui.table-head>Duration</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($logs as $log)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <div class="font-medium text-sm">{{ $log->user?->name ?? '—' }}</div>
                                <div class="text-xs text-muted-foreground">{{ $log->user?->email }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <span class="font-mono text-sm">{{ $log->ip_address }}</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $browserIcon = match($log->browser) {
                                        'Chrome'  => '🌐',
                                        'Firefox' => '🦊',
                                        'Safari'  => '🧭',
                                        'Edge'    => '🔷',
                                        'Opera'   => '🎭',
                                        default   => '🌍',
                                    };
                                @endphp
                                <span class="text-sm">{{ $browserIcon }} {{ $log->browser ?? '—' }}</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $deviceIcon = match($log->device) {
                                        'Mobile'  => '📱',
                                        'Tablet'  => '📟',
                                        default   => '🖥️',
                                    };
                                @endphp
                                <span class="text-sm">{{ $deviceIcon }} {{ $log->device ?? '—' }}</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-sm">{{ $log->platform ?? '—' }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="text-sm">{{ $log->created_at->format('M j, Y') }}</div>
                                <div class="text-xs text-muted-foreground font-mono">{{ $log->created_at->format('H:i:s') }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if ($log->logout_at)
                                    <div class="text-sm">{{ $log->logout_at->format('M j, Y') }}</div>
                                    <div class="text-xs text-muted-foreground font-mono">{{ $log->logout_at->format('H:i:s') }}</div>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-green-700 font-medium">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Active / not logged out
                                    </span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-sm text-muted-foreground">
                                {{ $log->duration ?? '—' }}
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="8" class="text-center py-12 text-muted-foreground">
                                No login records found.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>

            @if ($logs->hasPages())
                <div class="border-t p-3">{{ $logs->links() }}</div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
