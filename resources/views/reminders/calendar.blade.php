<x-app-layout>
    <x-slot:header>Reminders / Calendar</x-slot:header>

    <div class="space-y-4">
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Reminders</h1>
                <p class="text-sm text-muted-foreground">Calendar view — click a day to schedule.</p>
            </div>

            {{-- View toggle --}}
            <div class="inline-flex rounded-md border border-input p-0.5 text-sm">
                <a href="{{ route('reminders.index') }}" class="px-3 py-1 rounded text-muted-foreground hover:text-foreground">List</a>
                <span class="px-3 py-1 rounded bg-accent text-accent-foreground font-medium">Calendar</span>
            </div>
        </div>

        {{-- Month nav --}}
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">{{ $month->format('F Y') }}</h2>
            <div class="flex items-center gap-1">
                <a href="{{ route('reminders.calendar', ['month' => $prevMonth]) }}" class="h-8 w-8 grid place-items-center rounded-md border border-input hover:bg-accent">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <a href="{{ route('reminders.calendar') }}" class="h-8 px-3 grid place-items-center rounded-md border border-input hover:bg-accent text-sm font-medium">Today</a>
                <a href="{{ route('reminders.calendar', ['month' => $nextMonth]) }}" class="h-8 w-8 grid place-items-center rounded-md border border-input hover:bg-accent">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        {{-- Quick-add (Alpine dialog opened by clicking a day) --}}
        <div x-data="{
            open: false,
            date: '',
            time: '09:00',
            openOn(d) { this.date = d; this.open = true; this.$nextTick(() => this.$refs.title?.focus()); },
        }" x-on:keydown.escape.window="open = false">

            {{-- Calendar grid --}}
            <x-ui.card class="overflow-hidden">
                {{-- Weekday header --}}
                <div class="grid grid-cols-7 border-b text-[10px] uppercase tracking-wider text-muted-foreground bg-muted/30">
                    @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $wd)
                        <div class="px-3 py-2 text-left font-medium">{{ $wd }}</div>
                    @endforeach
                </div>

                {{-- Day cells --}}
                <div class="grid grid-cols-7">
                    @foreach ($days as $day)
                        @php
                            $key = $day->format('Y-m-d');
                            $dayReminders = $reminders->get($key, collect());
                            $inMonth = $day->month === $month->month;
                            $isToday = $day->isSameDay($today);
                            $isPast = $day->lt($today);
                        @endphp
                        <div @class([
                            'min-h-[110px] border-b border-r p-2 flex flex-col gap-1 cursor-pointer transition-colors',
                            'bg-card hover:bg-accent/30' => $inMonth,
                            'bg-muted/20 text-muted-foreground/60' => !$inMonth,
                        ])
                             @click="openOn('{{ $key }}')">
                            <div class="flex items-center justify-between">
                                <span @class([
                                    'inline-grid place-items-center h-6 w-6 rounded-full text-xs font-medium',
                                    'bg-primary text-primary-foreground' => $isToday,
                                    'text-foreground' => !$isToday && $inMonth,
                                    'text-muted-foreground/60' => !$inMonth,
                                ])>{{ $day->day }}</span>
                                @if ($dayReminders->count() > 3)
                                    <span class="text-[10px] text-muted-foreground">+{{ $dayReminders->count() - 3 }}</span>
                                @endif
                            </div>

                            <div class="space-y-0.5 flex-1 overflow-hidden">
                                @foreach ($dayReminders->take(3) as $r)
                                    @php
                                        $isOverdue = $r->isOverdue();
                                        $isComplete = $r->status === 'completed';
                                    @endphp
                                    <div @click.stop @class([
                                        'group flex items-center gap-1 rounded px-1.5 py-0.5 text-[11px] font-medium truncate',
                                        'bg-destructive/15 text-destructive' => $isOverdue,
                                        'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 line-through opacity-70' => $isComplete,
                                        'bg-primary/15 text-primary' => !$isOverdue && !$isComplete,
                                    ])>
                                        <span class="shrink-0 tabular-nums opacity-70">{{ $r->remind_at->format('H:i') }}</span>
                                        <span class="truncate">{{ $r->title }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            {{-- Inline schedule modal --}}
            <template x-teleport="body">
                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-background/80 backdrop-blur-sm"></div>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="relative w-full max-w-md rounded-lg border bg-popover p-6 shadow-2xl space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold">Schedule a reminder</h3>
                            <p class="text-sm text-muted-foreground" x-text="'On ' + new Date(date + 'T00:00:00').toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric' })"></p>
                        </div>
                        <form method="POST" action="{{ route('reminders.store') }}" class="space-y-3">
                            @csrf
                            <input type="hidden" name="remind_at" :value="date + 'T' + time" />
                            <div class="grid grid-cols-3 gap-2">
                                <div class="col-span-2 space-y-1.5">
                                    <x-ui.label for="cal_title">Title</x-ui.label>
                                    <x-ui.input id="cal_title" name="title" x-ref="title" required placeholder="Follow up with…" />
                                </div>
                                <div class="space-y-1.5">
                                    <x-ui.label for="cal_time">Time</x-ui.label>
                                    <x-ui.input id="cal_time" type="time" x-model="time" />
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="cal_desc">Note (optional)</x-ui.label>
                                <x-ui.textarea id="cal_desc" name="description" rows="2"></x-ui.textarea>
                            </div>
                            <label class="flex items-center gap-2 text-sm text-muted-foreground">
                                <input type="checkbox" name="notify_email" value="1" checked class="rounded border-input" /> Email me when due
                            </label>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="open = false" class="inline-flex h-9 items-center rounded-md border border-input px-4 text-sm hover:bg-accent">Cancel</button>
                                <x-ui.button type="submit">Schedule</x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>
