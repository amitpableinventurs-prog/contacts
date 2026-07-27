<x-app-layout>
    <x-slot:header>Contacts / Trash</x-slot:header>

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('contacts.index') }}" class="inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Contacts
                </a>
                <h1 class="text-2xl font-semibold tracking-tight mt-2">Trash</h1>
                <p class="text-sm text-muted-foreground">
                    Contacts moved to trash. Restore them or permanently delete them from the database.
                </p>
            </div>
            @if (auth()->user()->isSuperAdmin() && $contacts->total() > 0)
                <form method="POST" action="{{ route('contacts.trash.empty') }}"
                      onsubmit="return confirmDeleteWithPin(this, 'Permanently delete all {{ $contacts->total() }} trashed contact(s)? This cannot be undone.')">
                    @csrf
                    <input type="hidden" name="pin" value="" />
                    <x-ui.button type="submit" variant="destructive" size="sm">Empty trash</x-ui.button>
                </form>
            @endif
        </div>

        <x-ui.card>
            @if ($contacts->isEmpty())
                <x-ui.card-content class="text-sm text-muted-foreground text-center py-10">
                    Trash is empty.
                </x-ui.card-content>
            @else
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head class="hidden sm:table-cell">Phone / Email</x-ui.table-head>
                        <x-ui.table-head class="hidden md:table-cell">Deleted</x-ui.table-head>
                        <x-ui.table-head class="w-40"></x-ui.table-head>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @foreach ($contacts as $contact)
                            <x-ui.table-row>
                                <x-ui.table-cell>
                                    <div class="flex items-center gap-3">
                                        <x-ui.avatar :name="$contact->name" :src="$contact->photo" size="sm" />
                                        <div class="min-w-0 font-medium truncate">{{ $contact->name }}</div>
                                    </div>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden sm:table-cell text-sm text-muted-foreground">
                                    {{ $contact->phone ?: $contact->email ?: '—' }}
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden md:table-cell text-sm text-muted-foreground">
                                    {{ $contact->deleted_at?->diffForHumans() }}
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <div class="flex items-center justify-end gap-2">
                                        @can('restore', $contact)
                                            <form method="POST" action="{{ route('contacts.restore', $contact) }}">
                                                @csrf
                                                <x-ui.button type="submit" variant="outline" size="sm">Restore</x-ui.button>
                                            </form>
                                        @endcan
                                        @can('forceDelete', $contact)
                                            <form method="POST" action="{{ route('contacts.force-destroy', $contact) }}"
                                                  onsubmit="return confirmDeleteWithPin(this, 'Permanently delete {{ addslashes($contact->name) }}? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="pin" value="" />
                                                <x-ui.button type="submit" variant="destructive" size="sm">Delete forever</x-ui.button>
                                            </form>
                                        @endcan
                                    </div>
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @endforeach
                    </x-ui.table-body>
                </x-ui.table>

                <div class="border-t p-3">
                    {{ $contacts->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>

    @push('scripts')
    <script>
        function confirmDeleteWithPin(form, message) {
            if (!confirm(message)) return false;
            const pinInput = form.querySelector('input[name="pin"]');
            if (pinInput) {
                const pin = prompt('Enter PIN to confirm:');
                if (!pin) return false;
                pinInput.value = pin;
            }
            return true;
        }
    </script>
    @endpush
</x-app-layout>
