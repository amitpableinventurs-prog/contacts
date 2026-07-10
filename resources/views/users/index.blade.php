<x-app-layout>
    <x-slot:header>Users</x-slot:header>

    <div class="space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Users</h1>
                <p class="text-sm text-muted-foreground">Manage who can access this application.</p>
            </div>
            <a href="{{ route('users.create') }}">
                <x-ui.button>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add user
                </x-ui.button>
            </a>
        </div>

        <x-ui.card class="overflow-hidden">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row class="hover:bg-transparent">
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Email</x-ui.table-head>
                        <x-ui.table-head>Role</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Joined</x-ui.table-head>
                        <x-ui.table-head class="w-10"></x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($users as $user)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <a href="{{ route('users.show', $user) }}" class="flex items-center gap-3 group">
                                    <x-ui.avatar :name="$user->name" size="sm" />
                                    <div>
                                        <div class="font-medium group-hover:underline">{{ $user->name }}</div>
                                        @if ($user->id === auth()->id())
                                            <div class="text-xs text-muted-foreground">You</div>
                                        @endif
                                    </div>
                                </a>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-sm text-muted-foreground">{{ $user->email }}</x-ui.table-cell>
                            <x-ui.table-cell>
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
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if ($user->is_active ?? true)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Inactive
                                    </span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-sm text-muted-foreground">
                                {{ $user->created_at->format('M j, Y') }}
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.dropdown-menu align="end" width="w-44">
                                    <x-slot:trigger>
                                        <button class="rounded-md p-1.5 hover:bg-accent text-muted-foreground hover:text-foreground">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                        </button>
                                    </x-slot:trigger>
                                    <x-ui.dropdown-menu-item :href="route('users.show', $user)">View</x-ui.dropdown-menu-item>
                                    <x-ui.dropdown-menu-item :href="route('users.edit', $user)">Edit</x-ui.dropdown-menu-item>
                                    @if ($user->id !== auth()->id())
                                        <x-ui.dropdown-menu-separator />
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                            @csrf @method('DELETE')
                                            <x-ui.dropdown-menu-item as="button" type="submit" destructive>Delete</x-ui.dropdown-menu-item>
                                        </form>
                                    @endif
                                </x-ui.dropdown-menu>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-center py-12 text-muted-foreground">No users found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
            @if ($users->hasPages())
                <div class="border-t p-3">{{ $users->links() }}</div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
