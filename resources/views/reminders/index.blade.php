<x-app-layout>
    <x-slot:header>Reminders</x-slot:header>

    @php
    $sections = [
        'overdue' => ['label' => 'Overdue', 'color' => 'destructive'],
        'today' => ['label' => 'Today', 'color' => 'warning'],
        'this_week' => ['label' => 'This week', 'color' => 'default'],
        'later' => ['label' => 'Later', 'color' => 'secondary'],
    ];
    $total = collect($grouped)->sum(fn ($g) => $g->count());
    @endphp

    <div class="max-w-4xl space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Reminders</h1>
                <p class="text-sm text-muted-foreground">{{ $total }} pending follow-up{{ $total === 1 ? '' : 's' }}.</p>
            </div>
            <div class="inline-flex rounded-md border border-input p-0.5 text-sm">
                <span class="px-3 py-1 rounded bg-accent text-accent-foreground font-medium">List</span>
                <a href="{{ route('reminders.calendar') }}" class="px-3 py-1 rounded text-muted-foreground hover:text-foreground">Calendar</a>
            </div>
        </div>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Schedule a follow-up</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <form method="POST" action="{{ route('reminders.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @csrf
                    <div class="space-y-1.5 sm:col-span-2">
                        <x-ui.label for="title">Title</x-ui.label>
                        <x-ui.input id="title" name="title" required placeholder="Call Jane about Q3 proposal" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui.label for="remind_at">When</x-ui.label>
                        <x-ui.input id="remind_at" name="remind_at" type="datetime-local" required value="{{ now()->addDay()->format('Y-m-d\TH:i') }}" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui.label for="contact_search">Contact (optional)</x-ui.label>
                        <div x-data="{ q: '', sel: '', label: '', results: [],
                            async search() { if (this.q.length < 2) { this.results = []; return; }
                                const r = await fetch('{{ route('contacts.autocomplete') }}?q=' + encodeURIComponent(this.q));
                                this.results = await r.json(); },
                            pick(c) { this.sel = c.id; this.label = c.name; this.q = c.name; this.results = []; }
                        }" class="relative">
                            <x-ui.input x-model="q" @input.debounce.300ms="search" id="contact_search" placeholder="Search..." />
                            <input type="hidden" name="contact_id" :value="sel" />
                            <div x-show="results.length > 0" x-cloak class="absolute z-10 mt-1 w-full rounded-md border bg-popover shadow-lg max-h-48 overflow-auto">
                                <template x-for="c in results" :key="c.id">
                                    <button type="button" @click="pick(c)" class="block w-full text-left px-3 py-2 text-sm hover:bg-accent">
                                        <span class="font-medium" x-text="c.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-2 space-y-1.5">
                        <x-ui.label for="description">Note (optional)</x-ui.label>
                        <x-ui.textarea id="description" name="description" rows="2"></x-ui.textarea>
                    </div>
                    <div class="sm:col-span-2 flex flex-wrap items-center gap-4">
                        <label class="flex items-center gap-2 text-sm text-muted-foreground">
                            <input type="checkbox" name="notify_email" value="1" checked class="rounded border-input" /> Email me
                        </label>
                        <label class="flex items-center gap-2 text-sm text-muted-foreground">
                            <input type="checkbox" name="notify_sms" value="1" class="rounded border-input" /> SMS me (if my phone is set)
                        </label>
                        <div class="flex-1"></div>
                        <x-ui.button type="submit">Schedule</x-ui.button>
                    </div>
                </form>
                @error('title') <p class="text-xs text-destructive mt-2">{{ $message }}</p> @enderror
                @error('remind_at') <p class="text-xs text-destructive mt-2">{{ $message }}</p> @enderror
            </x-ui.card-content>
        </x-ui.card>

        @foreach ($sections as $key => $section)
            @if ($grouped[$key]->isNotEmpty())
                <x-ui.card class="overflow-hidden">
                    <x-ui.card-header class="flex flex-row items-center justify-between">
                        <x-ui.card-title>{{ $section['label'] }}</x-ui.card-title>
                        <x-ui.badge variant="{{ $section['color'] }}">{{ $grouped[$key]->count() }}</x-ui.badge>
                    </x-ui.card-header>
                    <x-ui.card-content class="p-0">
                        <ul class="divide-y">
                            @foreach ($grouped[$key] as $r)
                                <li class="flex items-start gap-3 p-4">
                                    <form method="POST" action="{{ route('reminders.complete', $r) }}" class="shrink-0 pt-0.5">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="h-5 w-5 rounded-full border border-input hover:bg-primary hover:border-primary group" aria-label="Mark done">
                                            <svg class="h-3 w-3 m-auto opacity-0 group-hover:opacity-100 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline justify-between gap-3">
                                            <p class="font-medium text-sm">{{ $r->title }}</p>
                                            <span @class([
                                                'text-xs shrink-0',
                                                'text-destructive font-medium' => $r->isOverdue(),
                                                'text-muted-foreground' => !$r->isOverdue(),
                                            ])>{{ $r->remind_at->diffForHumans() }}</span>
                                        </div>
                                        @if ($r->contact)
                                            <div class="text-xs text-muted-foreground">↳ <a href="{{ route('contacts.show', $r->contact) }}" class="hover:underline">{{ $r->contact->name }}</a></div>
                                        @endif
                                        @if ($r->description)
                                            <p class="text-xs text-muted-foreground mt-1">{{ $r->description }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('reminders.destroy', $r) }}" onsubmit="return confirm('Delete reminder?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-muted-foreground hover:text-destructive">Delete</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card-content>
                </x-ui.card>
            @endif
        @endforeach

        @if ($total === 0)
            <x-ui.card>
                <x-ui.card-content class="p-12 text-center">
                    <p class="text-sm text-muted-foreground">No pending reminders. Schedule one above.</p>
                </x-ui.card-content>
            </x-ui.card>
        @endif

        @if ($completed->isNotEmpty())
            <details class="group">
                <summary class="cursor-pointer text-sm text-muted-foreground hover:text-foreground select-none">
                    Recently completed ({{ $completed->count() }})
                </summary>
                <div class="mt-3">
                    <x-ui.card class="overflow-hidden">
                        <ul class="divide-y">
                            @foreach ($completed as $r)
                                <li class="flex items-center gap-3 p-3 opacity-70">
                                    <div class="h-5 w-5 rounded-full bg-success grid place-items-center shrink-0">
                                        <svg class="h-3 w-3 text-success-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="flex-1 text-sm line-through">{{ $r->title }}</span>
                                    <span class="text-xs text-muted-foreground">{{ $r->completed_at?->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>
                </div>
            </details>
        @endif
    </div>
</x-app-layout>
