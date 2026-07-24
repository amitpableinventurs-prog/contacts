<x-app-layout>
    <x-slot:header>Contacts / Delete tools</x-slot:header>

    <div class="space-y-4 max-w-2xl">
        <div>
            <a href="{{ route('contacts.index') }}" class="inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Contacts
            </a>
            <h1 class="text-2xl font-semibold tracking-tight mt-2">Bulk delete by count</h1>
            <p class="text-sm text-muted-foreground">
                Moves the oldest contacts in this workspace to trash. Super Admin only, PIN-protected.
            </p>
        </div>

        <x-ui.card>
            <x-ui.card-content class="p-6">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach ([500, 1000, 3000, 5000] as $count)
                        <form method="POST" action="{{ route('contacts.bulk') }}"
                              onsubmit="return confirmDeleteWithPin(this, 'Move {{ $count }} contacts to trash? This cannot be undone easily.')">
                            @csrf
                            <input type="hidden" name="action" value="delete_count" />
                            <input type="hidden" name="bulk_count" value="{{ $count }}" />
                            <input type="hidden" name="pin" value="" />
                            <x-ui.button type="submit" variant="outline" size="sm">
                                🗑 {{ number_format($count) }}
                            </x-ui.button>
                        </form>
                    @endforeach
                </div>
                <p class="text-xs text-muted-foreground mt-3">Deletes oldest contacts first.</p>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    @push('scripts')
    <script>
        function confirmDeleteWithPin(form, message) {
            if (!confirm(message)) return false;
            const pin = prompt('Enter PIN to confirm deletion:');
            if (!pin) return false;
            form.querySelector('input[name="pin"]').value = pin;
            return true;
        }
    </script>
    @endpush
</x-app-layout>
