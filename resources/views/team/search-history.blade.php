<x-app-layout>
    <x-slot:header>Team / {{ $user->name }} / Search history</x-slot:header>

    <div class="space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ $user->name }}'s searches</h1>
                <p class="text-sm text-muted-foreground">{{ $user->searches_used }} total searches performed.</p>
            </div>
            <a href="{{ route('team.index') }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent transition-colors focus-ring">
                Back to Team
            </a>
        </div>

        <x-ui.card class="overflow-hidden">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row class="hover:bg-transparent">
                        <x-ui.table-head>Query</x-ui.table-head>
                        <x-ui.table-head>Results</x-ui.table-head>
                        <x-ui.table-head>When</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($logs as $log)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-mono text-sm">{{ $log->query }}</x-ui.table-cell>
                            <x-ui.table-cell>{{ $log->results_count }}</x-ui.table-cell>
                            <x-ui.table-cell class="text-sm text-muted-foreground">
                                {{ $log->created_at->format('M j, Y H:i') }}
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="3" class="text-center py-12 text-muted-foreground">No searches recorded yet.</x-ui.table-cell>
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
