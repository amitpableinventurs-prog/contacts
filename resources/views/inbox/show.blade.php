<x-app-layout>
    <x-slot:header>Inbox / Message</x-slot:header>

    <div class="max-w-xl mx-auto space-y-4">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('inbox.index') }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent transition-colors focus-ring">
                Back to Inbox
            </a>
            <form method="POST" action="{{ route('inbox.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                @csrf @method('DELETE')
                <x-ui.button type="submit" variant="destructive" size="sm">Delete</x-ui.button>
            </form>
        </div>

        <x-ui.card>
            <x-ui.card-content class="p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <x-ui.avatar :name="$message->sender?->name ?? 'Unknown'" size="md" />
                    <div>
                        <div class="font-medium">{{ $message->sender?->name ?? 'Unknown' }}</div>
                        <div class="text-xs text-muted-foreground">
                            to {{ $message->recipient?->name ?? 'Unknown' }} · {{ $message->created_at->format('M j, Y H:i') }}
                        </div>
                    </div>
                </div>
                <p class="text-sm whitespace-pre-line">{{ $message->body }}</p>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-app-layout>
