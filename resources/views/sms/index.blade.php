<x-app-layout>
    <x-slot:header>Messages</x-slot:header>

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Messages</h1>
                <p class="text-sm text-muted-foreground">Two-way SMS conversations with your contacts.</p>
            </div>
            <a href="{{ route('contacts.index') }}">
                <x-ui.button variant="outline">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Start a conversation
                </x-ui.button>
            </a>
        </div>

        @if (config('twilio.fake'))
            <x-ui.card class="border-warning/40 bg-warning/5">
                <x-ui.card-content class="p-4 flex items-center gap-3 text-sm">
                    <svg class="h-4 w-4 shrink-0 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/></svg>
                    <div>
                        <span class="font-medium">Twilio fake mode</span>
                        <span class="text-muted-foreground">— outbound messages are recorded but not sent. Set TWILIO_SID/TOKEN/NUMBER in .env to enable real delivery.</span>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        @endif

        <x-ui.card class="overflow-hidden">
            @if ($conversations->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-muted grid place-items-center mb-3">
                        <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-base font-medium">No conversations yet</h3>
                    <p class="text-sm text-muted-foreground mt-1 mb-4">Open a contact to send the first message.</p>
                    <a href="{{ route('contacts.index') }}">
                        <x-ui.button>Browse contacts</x-ui.button>
                    </a>
                </div>
            @else
                <ul class="divide-y">
                    @foreach ($conversations as $contact)
                        @php $last = $contact->messages->first(); @endphp
                        <li>
                            <a href="{{ route('sms.show', $contact) }}" class="flex items-center gap-4 p-4 hover:bg-accent/50 transition-colors">
                                <x-ui.avatar :name="$contact->name" :src="$contact->photo" />
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <p class="font-medium truncate">{{ $contact->name }}</p>
                                        <span class="text-xs text-muted-foreground shrink-0">{{ $last?->sent_at?->diffForHumans(short: true) }}</span>
                                    </div>
                                    <p class="text-sm text-muted-foreground truncate">
                                        @if ($last)
                                            @if ($last->direction === 'outbound') You: @endif
                                            {{ $last->body }}
                                        @endif
                                    </p>
                                </div>
                                @if ($contact->messages_count > 1)
                                    <x-ui.badge variant="outline">{{ $contact->messages_count }}</x-ui.badge>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t p-3">
                    {{ $conversations->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
