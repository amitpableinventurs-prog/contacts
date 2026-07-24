<x-app-layout>
    <x-slot:header>Messages / Sent</x-slot:header>

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Sent messages</h1>
                <p class="text-sm text-muted-foreground">Every SMS/WhatsApp message sent to a contact, across all conversations.</p>
            </div>
            <a href="{{ route('sms.index') }}" class="inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to conversations
            </a>
        </div>

        <x-ui.card class="overflow-hidden">
            @if ($messages->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-muted grid place-items-center mb-3">
                        <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <h3 class="text-base font-medium">No sent messages yet</h3>
                    <p class="text-sm text-muted-foreground mt-1">Messages you send to contacts will show up here.</p>
                </div>
            @else
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row class="hover:bg-transparent">
                            <x-ui.table-head>To</x-ui.table-head>
                            <x-ui.table-head>Message</x-ui.table-head>
                            <x-ui.table-head>Channel</x-ui.table-head>
                            <x-ui.table-head>Status</x-ui.table-head>
                            <x-ui.table-head class="hidden md:table-cell">Sent</x-ui.table-head>
                            @if (auth()->user()->hasRole(\App\Support\Roles::SUPER_ADMIN, \App\Support\Roles::ADMIN))
                                <x-ui.table-head class="w-px"></x-ui.table-head>
                            @endif
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @foreach ($messages as $message)
                            <x-ui.table-row>
                                <x-ui.table-cell>
                                    @if ($message->contact)
                                        <a href="{{ route('sms.show', $message->contact) }}" class="flex items-center gap-2 group">
                                            <x-ui.avatar :name="$message->contact->name" size="sm" />
                                            <div>
                                                <div class="font-medium group-hover:underline truncate">{{ $message->contact->name }}</div>
                                                <div class="text-xs text-muted-foreground truncate">{{ $message->to_number }}</div>
                                            </div>
                                        </a>
                                    @else
                                        <span class="text-sm text-muted-foreground">{{ $message->to_number }}</span>
                                    @endif
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <div class="text-sm truncate max-w-md">{{ \Illuminate\Support\Str::limit($message->body, 80) }}</div>
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <span class="text-sm capitalize">{{ $message->channel }}</span>
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <x-ui.badge variant="{{ $message->status === 'sent' || $message->status === 'delivered' ? 'success' : ($message->status === 'failed' ? 'destructive' : 'secondary') }}">
                                        {{ $message->status }}
                                    </x-ui.badge>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden md:table-cell text-sm text-muted-foreground">
                                    {{ $message->sent_at?->diffForHumans() ?? '—' }}
                                </x-ui.table-cell>
                                @if (auth()->user()->hasRole(\App\Support\Roles::SUPER_ADMIN, \App\Support\Roles::ADMIN))
                                    <x-ui.table-cell>
                                        <form method="POST" action="{{ route('sms.destroy', $message) }}" onsubmit="return confirm('Delete this sent message? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md p-1.5 text-muted-foreground hover:text-destructive hover:bg-destructive/10" title="Delete">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </x-ui.table-cell>
                                @endif
                            </x-ui.table-row>
                        @endforeach
                    </x-ui.table-body>
                </x-ui.table>
                <div class="border-t p-3">
                    {{ $messages->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
