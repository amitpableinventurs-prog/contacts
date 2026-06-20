<x-app-layout>
    <x-slot:header>Workspace / Audit log</x-slot:header>

    <div class="max-w-4xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Audit log</h1>
            <p class="text-sm text-muted-foreground">Every contact change, captured automatically.</p>
        </div>

        <x-ui.card class="overflow-hidden">
            @if ($activities->isEmpty())
                <x-ui.card-content>
                    <p class="text-sm text-muted-foreground text-center py-12">No tracked activity yet.</p>
                </x-ui.card-content>
            @else
                <ul class="divide-y">
                    @foreach ($activities as $a)
                        @php
                            $causerName = $a->causer?->name ?? 'system';
                            $event = $a->event ?? $a->description;
                            $changes = $a->properties['attributes'] ?? [];
                            $old = $a->properties['old'] ?? [];
                        @endphp
                        <li class="p-4">
                            <div class="flex items-start gap-3">
                                <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-muted text-xs font-medium">
                                    {{ collect(explode(' ', $causerName))->map(fn ($w) => $w[0] ?? '')->take(2)->implode('') ?: '?' }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <p class="text-sm">
                                            <span class="font-medium">{{ $causerName }}</span>
                                            <span class="text-muted-foreground">{{ $event }}d</span>
                                            @if ($a->subject)
                                                <a href="{{ route('contacts.show', $a->subject) }}" class="font-medium hover:underline">{{ $a->subject->name ?? 'contact #'.$a->subject_id }}</a>
                                            @endif
                                        </p>
                                        <span class="text-xs text-muted-foreground shrink-0">{{ $a->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if (! empty($changes))
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 text-xs">
                                            @foreach ($changes as $field => $newVal)
                                                @if (in_array($field, ['updated_at', 'last_contacted_at'])) @continue @endif
                                                <div class="flex gap-2">
                                                    <span class="text-muted-foreground min-w-[100px]">{{ $field }}</span>
                                                    <span class="truncate">
                                                        @if (array_key_exists($field, $old))
                                                            <span class="line-through text-muted-foreground/70">{{ \Illuminate\Support\Str::limit((string) $old[$field], 30) }}</span>
                                                            <span class="text-foreground">→ {{ \Illuminate\Support\Str::limit((string) $newVal, 30) }}</span>
                                                        @else
                                                            <span class="text-foreground">{{ \Illuminate\Support\Str::limit((string) $newVal, 60) }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t p-3">{{ $activities->links() }}</div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
