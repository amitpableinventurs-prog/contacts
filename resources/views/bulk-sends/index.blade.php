<x-app-layout :title="'Bulk sends'">
    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Bulk sends</h1>
                <p class="text-sm text-muted-foreground mt-0.5">Every campaign you've sent — newest first.</p>
            </div>
        </div>

        @if ($sends->isEmpty())
            <x-ui.card>
                <x-ui.card-content class="p-12 text-center">
                    <div class="grid h-12 w-12 place-items-center mx-auto rounded-full bg-muted text-muted-foreground mb-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <h2 class="font-semibold mb-1">No bulk sends yet</h2>
                    <p class="text-sm text-muted-foreground mb-4">Select contacts on the contacts list, then click <strong>Send message</strong>.</p>
                    <a href="{{ route('contacts.index') }}" class="inline-flex h-9 items-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                        Go to contacts →
                    </a>
                </x-ui.card-content>
            </x-ui.card>
        @else
            <x-ui.card>
                <x-ui.card-content class="p-0">
                    <x-ui.table>
                        <x-ui.table-header>
                            <x-ui.table-row>
                                <x-ui.table-head>When</x-ui.table-head>
                                <x-ui.table-head class="hidden md:table-cell">Channel</x-ui.table-head>
                                <x-ui.table-head>Subject / preview</x-ui.table-head>
                                <x-ui.table-head class="hidden md:table-cell text-right">Sent</x-ui.table-head>
                                <x-ui.table-head class="hidden md:table-cell text-right">Failed</x-ui.table-head>
                                <x-ui.table-head>Status</x-ui.table-head>
                            </x-ui.table-row>
                        </x-ui.table-header>
                        <x-ui.table-body>
                            @foreach ($sends as $s)
                                <x-ui.table-row class="cursor-pointer hover:bg-muted/40" onclick="window.location='{{ route('bulk-sends.show', $s) }}'">
                                    <x-ui.table-cell class="whitespace-nowrap text-sm">
                                        <div class="font-medium">{{ $s->created_at->format('M j, Y') }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $s->created_at->format('g:i a') }} · by {{ $s->user?->name ?: '—' }}</div>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="hidden md:table-cell">
                                        <x-ui.badge variant="secondary">{{ strtoupper($s->channel) }}</x-ui.badge>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="max-w-sm">
                                        @if ($s->subject)
                                            <div class="font-medium text-sm truncate">{{ $s->subject }}</div>
                                        @endif
                                        <div class="text-xs text-muted-foreground truncate">{{ \Illuminate\Support\Str::limit($s->body, 80) }}</div>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="hidden md:table-cell text-right text-sm tabular-nums">
                                        {{ $s->sent_count }} / {{ $s->total_count }}
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="hidden md:table-cell text-right text-sm tabular-nums">
                                        @if ($s->failed_count > 0)
                                            <span class="text-destructive">{{ $s->failed_count }}</span>
                                        @else
                                            <span class="text-muted-foreground">0</span>
                                        @endif
                                    </x-ui.table-cell>
                                    <x-ui.table-cell>
                                        @php $st = $s->status(); @endphp
                                        <x-ui.badge variant="{{ $st === 'completed' ? 'success' : ($st === 'running' ? 'default' : 'secondary') }}">
                                            {{ $st }}
                                        </x-ui.badge>
                                    </x-ui.table-cell>
                                </x-ui.table-row>
                            @endforeach
                        </x-ui.table-body>
                    </x-ui.table>
                </x-ui.card-content>
            </x-ui.card>

            <div class="mt-4">{{ $sends->links() }}</div>
        @endif
    </div>
</x-app-layout>
