<x-app-layout>
    <x-slot:header>Inbox</x-slot:header>

    <div class="space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Inbox</h1>
                <p class="text-sm text-muted-foreground">Messages from other users.</p>
            </div>
            <a href="{{ route('inbox.create') }}">
                <x-ui.button>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New message
                </x-ui.button>
            </a>
        </div>

        <x-ui.card class="overflow-hidden">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row class="hover:bg-transparent">
                        <x-ui.table-head>From</x-ui.table-head>
                        <x-ui.table-head>Message</x-ui.table-head>
                        <x-ui.table-head class="hidden sm:table-cell">When</x-ui.table-head>
                        <x-ui.table-head class="w-10"></x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($messages as $message)
                        <x-ui.table-row class="{{ $message->read_at ? '' : 'font-medium bg-primary/5' }}">
                            <x-ui.table-cell>
                                <a href="{{ route('inbox.show', $message) }}" class="flex items-center gap-2 group">
                                    <x-ui.avatar :name="$message->sender?->name ?? 'Unknown'" size="xs" />
                                    <span class="group-hover:underline">{{ $message->sender?->name ?? 'Unknown' }}</span>
                                    @if (! $message->read_at)
                                        <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
                                    @endif
                                </a>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-sm text-muted-foreground">
                                <a href="{{ route('inbox.show', $message) }}">{{ \Illuminate\Support\Str::limit($message->body, 80) }}</a>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="hidden sm:table-cell text-sm text-muted-foreground">
                                {{ $message->created_at->diffForHumans() }}
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <form method="POST" action="{{ route('inbox.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-muted-foreground hover:text-destructive" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="4" class="text-center py-12 text-muted-foreground">No messages yet.</x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
            @if ($messages->hasPages())
                <div class="border-t p-3">{{ $messages->links() }}</div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
