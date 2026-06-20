<x-app-layout>
    <x-slot:header>Contacts / Import / Map columns</x-slot:header>

    <div class="max-w-5xl mx-auto space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Map columns</h1>
            <p class="text-sm text-muted-foreground">Match each column in your CSV to a contact field. Auto-detected guesses are pre-selected.</p>
        </div>

        <form method="POST" action="{{ route('imports.store') }}">
            @csrf
            <input type="hidden" name="file" value="{{ $file }}" />

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Preview</x-ui.card-title>
                    <x-ui.card-description>Showing the first {{ count($sample) }} row(s). Pick a destination field per column.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="p-0 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/30">
                            <tr class="text-left">
                                @foreach ($headers as $idx => $header)
                                    <th class="p-3 align-bottom min-w-[180px]">
                                        <div class="text-xs uppercase tracking-wide text-muted-foreground mb-2">{{ $header ?: '(blank)' }}</div>
                                        <select name="mapping[{{ $idx }}]" class="flex h-9 w-full rounded-md border border-input bg-background px-2 text-sm focus-ring">
                                            <option value="">— Skip —</option>
                                            @foreach ($fields as $key => $label)
                                                <option value="{{ $key }}" @selected(($autoMap[$idx] ?? '') === $key)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($sample as $row)
                                <tr>
                                    @foreach ($headers as $idx => $header)
                                        <td class="p-3 align-top text-foreground/80 whitespace-pre-wrap break-words">{{ \Illuminate\Support\Str::limit($row[$idx] ?? '', 80) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-ui.card-content>
            </x-ui.card>

            <x-ui.card class="mt-4">
                <x-ui.card-content class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 rounded-md border border-input p-3">
                        <input type="checkbox" name="has_header" value="1" checked class="rounded border-input" />
                        <div class="text-sm">
                            <div class="font-medium">First row is a header</div>
                            <div class="text-xs text-muted-foreground">Skip row 1 when importing.</div>
                        </div>
                    </label>
                    <div class="space-y-1.5">
                        <x-ui.label for="group_id">Optional: assign all imported contacts to a group</x-ui.label>
                        <select id="group_id" name="group_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                            <option value="">No group</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            @error('mapping')
                <p class="mt-2 text-sm text-destructive">{{ $message }}</p>
            @enderror

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('imports.form') }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent transition-colors focus-ring">Back</a>
                <x-ui.button type="submit">Import contacts</x-ui.button>
            </div>
        </form>
    </div>
</x-app-layout>
