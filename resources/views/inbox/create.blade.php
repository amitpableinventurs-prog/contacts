<x-app-layout>
    <x-slot:header>Inbox / New message</x-slot:header>

    <div class="max-w-xl mx-auto space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">New message</h1>
        </div>

        <x-ui.card>
            <x-ui.card-content class="p-6">
                <form method="POST" action="{{ route('inbox.store') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <x-ui.label for="recipient_id">To</x-ui.label>
                        <select id="recipient_id" name="recipient_id" required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                            <option value="">Select a recipient…</option>
                            @foreach ($recipients as $recipient)
                                <option value="{{ $recipient->id }}" @selected(old('recipient_id') == $recipient->id)>
                                    {{ $recipient->name }} ({{ ucwords(str_replace('_', ' ', $recipient->role)) }})
                                </option>
                            @endforeach
                        </select>
                        @error('recipient_id') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <x-ui.label for="body">Message</x-ui.label>
                        <x-ui.textarea id="body" name="body" rows="6" required>{{ old('body') }}</x-ui.textarea>
                        @error('body') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('inbox.index') }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent transition-colors focus-ring">Cancel</a>
                        <x-ui.button type="submit">Send</x-ui.button>
                    </div>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-app-layout>
