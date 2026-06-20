<x-app-layout>
    <x-slot:header>Contacts / {{ $contact->name }} / Merge</x-slot:header>

    <div class="max-w-3xl mx-auto space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Merge duplicates</h1>
            <p class="text-sm text-muted-foreground">
                Selected contacts are merged into <span class="font-medium">{{ $contact->name }}</span>.
                Blank fields are filled from duplicates; tags are combined; messages and emails move over; duplicates are then deleted.
            </p>
        </div>

        @if ($duplicates->isEmpty())
            <x-ui.card>
                <x-ui.card-content class="p-8 text-center">
                    <p class="text-sm text-muted-foreground">No potential duplicates found for this contact.</p>
                    <a href="{{ route('contacts.show', $contact) }}" class="inline-block mt-3">
                        <x-ui.button variant="outline">Back to contact</x-ui.button>
                    </a>
                </x-ui.card-content>
            </x-ui.card>
        @else
            <form method="POST" action="{{ route('contacts.merge.store', $contact) }}">
                @csrf
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Keeping</x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="flex items-center gap-3 p-3 rounded-md border border-primary/30 bg-primary/5">
                            <x-ui.avatar :name="$contact->name" :src="$contact->photo" />
                            <div class="flex-1 min-w-0">
                                <p class="font-medium">{{ $contact->name }}</p>
                                <p class="text-xs text-muted-foreground">{{ $contact->email ?? $contact->phone ?? '—' }}</p>
                            </div>
                            <x-ui.badge>Target</x-ui.badge>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="mt-4">
                    <x-ui.card-header>
                        <x-ui.card-title>Merge into target</x-ui.card-title>
                        <x-ui.card-description>{{ $duplicates->count() }} potential {{ \Illuminate\Support\Str::plural('duplicate', $duplicates->count()) }} found.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="p-0">
                        <ul class="divide-y">
                            @foreach ($duplicates as $dup)
                                <li class="flex items-center gap-3 p-3">
                                    <input type="checkbox" name="duplicate_ids[]" value="{{ $dup->id }}" checked class="rounded border-input" />
                                    <x-ui.avatar :name="$dup->name" :src="$dup->photo" size="sm" />
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-sm">{{ $dup->name }}</p>
                                        <p class="text-xs text-muted-foreground truncate">
                                            {{ $dup->email ?? '—' }} · {{ $dup->phone ?? '—' }} · {{ $dup->company ?? '—' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('contacts.show', $dup) }}" target="_blank" class="text-xs text-muted-foreground hover:text-foreground">View</a>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card-content>
                </x-ui.card>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <a href="{{ route('contacts.show', $contact) }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent transition-colors focus-ring">Cancel</a>
                    <x-ui.button type="submit" onclick="return confirm('Merge the selected contacts? This deletes them.')">Merge selected</x-ui.button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
