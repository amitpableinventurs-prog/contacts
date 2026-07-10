<x-app-layout>
    <x-slot:header>Users / {{ $user->name }}</x-slot:header>

    <div class="space-y-4">
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                <x-ui.avatar :name="$user->name" :src="$user->photo" size="lg" />
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-semibold tracking-tight">{{ $user->name }}</h1>
                        @php
                            $roleColors = [
                                'super_admin' => 'bg-purple-100 text-purple-800',
                                'admin'       => 'bg-blue-100 text-blue-800',
                                'manager'     => 'bg-green-100 text-green-800',
                                'clerk'       => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucwords(str_replace('_', ' ', $user->role ?? 'clerk')) }}
                        </span>
                        @if ($user->is_active ?? true)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Inactive
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-muted-foreground">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('users.index') }}">
                    <x-ui.button variant="outline">Back to users</x-ui.button>
                </a>
                @if ($canEdit)
                    <a href="{{ route('users.edit', $user) }}">
                        <x-ui.button>Edit user</x-ui.button>
                    </a>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <x-ui.card>
                <x-ui.card-content class="p-4">
                    <div class="text-xs text-muted-foreground">Searches used</div>
                    <div class="text-2xl font-semibold mt-1">
                        {{ number_format($user->searches_used ?? 0) }}
                        <span class="text-sm font-normal text-muted-foreground">
                            / {{ ($user->search_quota ?? 0) > 0 ? number_format($user->search_quota) : 'unlimited' }}
                        </span>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
            <x-ui.card>
                <x-ui.card-content class="p-4">
                    <div class="text-xs text-muted-foreground">Total activity entries</div>
                    <div class="text-2xl font-semibold mt-1">{{ number_format($logCount) }}</div>
                </x-ui.card-content>
            </x-ui.card>
            <x-ui.card>
                <x-ui.card-content class="p-4">
                    <div class="text-xs text-muted-foreground">Logins recorded</div>
                    <div class="text-2xl font-semibold mt-1">{{ number_format($loginCount) }}</div>
                </x-ui.card-content>
            </x-ui.card>
            <x-ui.card>
                <x-ui.card-content class="p-4">
                    <div class="text-xs text-muted-foreground">Joined</div>
                    <div class="text-2xl font-semibold mt-1">{{ $user->created_at->format('M j, Y') }}</div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        {{-- Recent activity --}}
        <x-ui.card class="overflow-hidden">
            <div class="flex items-center justify-between px-4 pt-4">
                <h2 class="text-base font-semibold">Recent activity</h2>
                @can('view-audit')
                    <a href="{{ route('activity-logs.index', ['user_id' => $user->id]) }}" class="text-sm text-muted-foreground hover:text-foreground">
                        View all logs →
                    </a>
                @endcan
            </div>
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row class="hover:bg-transparent">
                        <x-ui.table-head>Action</x-ui.table-head>
                        <x-ui.table-head>Entity</x-ui.table-head>
                        <x-ui.table-head>Details</x-ui.table-head>
                        <x-ui.table-head>When</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($recentLogs as $log)
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
                        @endphp
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $actionColor }}">
                                    {{ ucwords(str_replace(['.', '_'], ' ', $log->action)) }}
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
                            <x-ui.table-cell colspan="4" class="text-center py-10 text-muted-foreground">
                                No activity recorded for this user yet.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card>
    </div>
</x-app-layout>
