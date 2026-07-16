<x-app-layout>
    <x-slot:header>Team</x-slot:header>

    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                @if (auth()->user()->isManager())
                    Your Clerks
                @else
                    Managers &amp; Clerks
                @endif
            </h1>
            <p class="text-sm text-muted-foreground">Search count and recent search history for your team.</p>
        </div>

        <x-ui.card class="overflow-hidden">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row class="hover:bg-transparent">
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head class="hidden sm:table-cell">Email</x-ui.table-head>
                        @if (!auth()->user()->isManager())
                            <x-ui.table-head>Role</x-ui.table-head>
                        @endif
                        <x-ui.table-head class="hidden md:table-cell">Status</x-ui.table-head>
                        <x-ui.table-head>Search count</x-ui.table-head>
                        <x-ui.table-head class="w-10"></x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($members as $member)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :name="$member->name" size="sm" />
                                    <div class="font-medium">{{ $member->name }}</div>
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="hidden sm:table-cell text-sm text-muted-foreground">{{ $member->email }}</x-ui.table-cell>
                            @if (!auth()->user()->isManager())
                                <x-ui.table-cell>
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $member->role === 'manager' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucwords($member->role) }}
                                    </span>
                                </x-ui.table-cell>
                            @endif
                            <x-ui.table-cell class="hidden md:table-cell">
                                @if ($member->is_active ?? true)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Inactive
                                    </span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="outline">{{ $member->searches_used }}</x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <a href="{{ route('team.search-history', $member) }}" class="text-xs text-primary hover:underline whitespace-nowrap">
                                    View history
                                </a>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="{{ !auth()->user()->isManager() ? 6 : 5 }}" class="text-center py-12 text-muted-foreground">No team members found.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card>
    </div>
</x-app-layout>
