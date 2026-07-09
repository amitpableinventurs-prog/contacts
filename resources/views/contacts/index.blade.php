<x-app-layout>
    <x-slot:header>Contacts</x-slot:header>

    @php $isClerk = auth()->user()->isClerk(); @endphp

    <div class="space-y-4" x-data="{
        selected: [],
        get all() { return Array.from(document.querySelectorAll('[data-contact-checkbox]')).map(el => +el.value); },
        toggleAll(checked) {
            this.selected = checked ? this.all : [];
        },
        toggle(id, checked) {
            if (checked) this.selected.push(id);
            else this.selected = this.selected.filter(i => i !== id);
        },
        isChecked(id) { return this.selected.includes(id); },
    }">

        {{-- Pending approvals banner (manager+) --}}
        @if (($pendingCount ?? 0) > 0)
            <a href="{{ route('contacts.pending') }}"
               class="flex items-center gap-3 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm hover:bg-yellow-100 transition-colors">
                <svg class="h-5 w-5 shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <span>
                    <span class="font-semibold text-yellow-800">{{ $pendingCount }} contact{{ $pendingCount > 1 ? 's' : '' }} pending approval</span>
                    <span class="text-yellow-700"> — submitted by Clerks. Click to review.</span>
                </span>
                <svg class="h-4 w-4 ml-auto text-yellow-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @endif

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Contacts</h1>
                @if ($isClerk)
                    <p class="text-sm text-muted-foreground">Search contacts by phone number.</p>
                @else
                    <p class="text-sm text-muted-foreground">
                        {{ $contacts->total() }} {{ \Illuminate\Support\Str::plural('contact', $contacts->total()) }} in this workspace
                    </p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @can('manage-imports')
                    <a href="{{ route('imports.form') }}">
                        <x-ui.button variant="outline">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Import
                        </x-ui.button>
                    </a>
                @endcan
                @can('manage-export')
                    <a href="{{ route('workspace.export') }}">
                        <x-ui.button variant="outline">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Export
                        </x-ui.button>
                    </a>
                @endcan
                @can('create', \App\Models\Contact::class)
                    <a href="{{ route('contacts.create') }}">
                        <x-ui.button>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add contact
                        </x-ui.button>
                    </a>
                @endcan
            </div>
        </div>

        {{-- Search bar (for clerks it only appears after a search — the empty-state card below carries the form until then) --}}
        @if (! $isClerk || ($clerkSearched ?? true))
        <x-ui.card>
            <x-ui.card-content class="p-4">
                <form method="GET" action="{{ route('contacts.index') }}">
                    <div class="flex flex-wrap items-center gap-2">
                        @unless ($isClerk)
                            <div class="flex-1 min-w-[160px]">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                                    <x-ui.input name="q" value="{{ request('q') }}" placeholder="Name, email, company, city…" class="pl-9" />
                                </div>
                            </div>
                        @endunless
                        <div class="{{ $isClerk ? 'w-full sm:w-72' : 'w-full sm:w-44' }}">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <x-ui.input name="number" value="{{ request('number') }}" placeholder="Phone number…" class="pl-9" />
                            </div>
                        </div>
                        @unless ($isClerk)
                            <select name="group_id" class="flex h-9 w-full sm:w-40 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                                <option value="">All groups</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" @selected(request('group_id') == $group->id)>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        @endunless
                        <x-ui.button type="submit" variant="secondary">Search</x-ui.button>
                        @if (request()->hasAny(['q','number','group_id','tags']))
                            <a href="{{ route('contacts.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Clear</a>
                        @endif
                    </div>
                    {{-- Tag chips --}}
                    @if ($tags->isNotEmpty())
                        <div class="flex flex-wrap items-center gap-2 pt-3">
                            <span class="text-xs text-muted-foreground">Tags:</span>
                            @php $activeTags = (array) request('tags', []); @endphp
                            @foreach ($tags as $tag)
                                @php
                                    $isActive = in_array($tag->id, $activeTags);
                                    $newTags  = $isActive ? array_diff($activeTags, [$tag->id]) : array_merge($activeTags, [$tag->id]);
                                    $href     = request()->fullUrlWithQuery(['tags' => array_values($newTags)]);
                                @endphp
                                <a href="{{ $href }}" class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium transition-colors {{ $isActive ? 'bg-primary text-primary-foreground border-primary' : 'border-input hover:bg-accent' }}">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </form>
            </x-ui.card-content>
        </x-ui.card>
        @endif

        {{-- Bulk action bar --}}
        <div x-show="selected.length > 0"
             x-cloak
             x-transition
             class="sticky top-14 z-20 flex flex-wrap items-center gap-2 rounded-lg border bg-card px-3 py-2 shadow-sm">
            <span class="text-sm">
                <span class="font-medium" x-text="selected.length"></span> selected
            </span>
            <div class="flex-1"></div>

            {{-- Add to group — manager+ only --}}
            @if (!auth()->user()->isClerk() && $groups->isNotEmpty())
                <x-ui.dropdown-menu align="end" width="w-56">
                    <x-slot:trigger>
                        <x-ui.button type="button" variant="outline" size="sm">
                            Add to group
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </x-ui.button>
                    </x-slot:trigger>
                    @foreach ($groups as $group)
                        <form method="POST" action="{{ route('contacts.bulk') }}">
                            @csrf
                            <input type="hidden" name="action" value="group" />
                            <input type="hidden" name="group_id" value="{{ $group->id }}" />
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="contact_ids[]" :value="id" />
                            </template>
                            <x-ui.dropdown-menu-item as="button" type="submit">
                                <span class="h-2 w-2 rounded-full" style="background:{{ $group->color ?: '#a855f7' }}"></span>
                                {{ $group->name }}
                            </x-ui.dropdown-menu-item>
                        </form>
                    @endforeach
                </x-ui.dropdown-menu>
            @endif

            {{-- Add tag — manager+ only --}}
            @if (!auth()->user()->isClerk() && $tags->isNotEmpty())
                <x-ui.dropdown-menu align="end" width="w-56">
                    <x-slot:trigger>
                        <x-ui.button type="button" variant="outline" size="sm">
                            Add tag
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </x-ui.button>
                    </x-slot:trigger>
                    @foreach ($tags as $tag)
                        <form method="POST" action="{{ route('contacts.bulk') }}">
                            @csrf
                            <input type="hidden" name="action" value="tag" />
                            <input type="hidden" name="tag_id" value="{{ $tag->id }}" />
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="contact_ids[]" :value="id" />
                            </template>
                            <x-ui.dropdown-menu-item as="button" type="submit">{{ $tag->name }}</x-ui.dropdown-menu-item>
                        </form>
                    @endforeach
                </x-ui.dropdown-menu>
            @endif

            {{-- Send bulk message — manager+ only --}}
            @if (!auth()->user()->isClerk())
                <a :href="'{{ route('bulk-sends.compose') }}?contact_ids=' + selected.join(',')"
                   class="inline-flex h-8 items-center gap-1.5 rounded-md bg-primary px-3 text-xs font-medium text-primary-foreground shadow-sm hover:bg-primary/90 transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Send message
                </a>
            @endif

            {{-- Trash (soft delete) — all roles --}}
            <form method="POST" action="{{ route('contacts.bulk') }}" class="flex gap-2">
                @csrf
                <input type="hidden" name="action" value="delete" />
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="contact_ids[]" :value="id" />
                </template>
                <x-ui.button type="button" variant="ghost" size="sm" @click="selected = []">Cancel</x-ui.button>
                <x-ui.button type="submit" variant="destructive" size="sm" onclick="return confirm('Move selected contacts to trash?')">
                    🗑 Move to trash
                </x-ui.button>
            </form>
        </div>

        {{-- Bulk delete by count (Admin / Manager only) --}}
        @if (!auth()->user()->isClerk())
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm text-muted-foreground">Bulk delete:</span>
                @foreach ([500, 1000, 3000, 5000] as $count)
                    <form method="POST" action="{{ route('contacts.bulk') }}"
                          onsubmit="return confirm('Move {{ $count }} contacts to trash? This cannot be undone easily.')">
                        @csrf
                        <input type="hidden" name="action" value="delete_count" />
                        <input type="hidden" name="bulk_count" value="{{ $count }}" />
                        <x-ui.button type="submit" variant="outline" size="sm">
                            🗑 {{ number_format($count) }}
                        </x-ui.button>
                    </form>
                @endforeach
                <span class="text-xs text-muted-foreground">(deletes oldest contacts first)</span>
            </div>
        @endif

        {{-- Table --}}
        <x-ui.card class="overflow-hidden">
            @if ($contacts->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-muted grid place-items-center mb-3">
                        <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    @if ($isClerk)
                        @if (! ($clerkSearched ?? true))
                            <h3 class="text-base font-medium">Search by phone number</h3>
                            <form method="GET" action="{{ route('contacts.index') }}" class="mx-auto mt-8 flex w-full max-w-sm items-center gap-2">
                                <div class="relative flex-1">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <x-ui.input name="number" placeholder="Phone number…" class="pl-9" autofocus />
                                </div>
                                <x-ui.button type="submit">Search</x-ui.button>
                            </form>
                        @else
                            <h3 class="text-base font-medium">Enter correct number</h3>
                        @endif
                    @else
                        <h3 class="text-base font-medium">No contacts yet</h3>
                        <p class="text-sm text-muted-foreground mt-1 mb-4">Add your first contact to get started.</p>
                        @can('create', \App\Models\Contact::class)
                            <a href="{{ route('contacts.create') }}">
                                <x-ui.button>Add contact</x-ui.button>
                            </a>
                        @endcan
                    @endif
                </div>
            @else
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row class="hover:bg-transparent">
                            @unless ($isClerk)
                                <x-ui.table-head class="w-10">
                                    <input type="checkbox"
                                           @change="toggleAll($event.target.checked)"
                                           :checked="selected.length === all.length && all.length > 0"
                                           class="rounded border-input" />
                                </x-ui.table-head>
                            @endunless
                            <x-ui.table-head>Name</x-ui.table-head>
                            <x-ui.table-head class="hidden md:table-cell">Company</x-ui.table-head>
                            <x-ui.table-head class="hidden lg:table-cell">Group</x-ui.table-head>
                            <x-ui.table-head class="hidden xl:table-cell">Tags</x-ui.table-head>
                            <x-ui.table-head class="hidden xl:table-cell">Added by</x-ui.table-head>
                            <x-ui.table-head class="w-10"></x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @foreach ($contacts as $contact)
                            @php
                                // Row background: banned > suspended > rating
                                if ($contact->status === 'banned') {
                                    $rowStyle = 'background-color:#fef2f2;border-left:3px solid #ef4444;';
                                } elseif ($contact->status === 'suspended') {
                                    $rowStyle = 'background-color:#fff7ed;border-left:3px solid #f97316;';
                                } elseif ((int)$contact->rating === 5) {
                                    $rowStyle = 'background-color:#f0fdf4;border-left:3px solid #16a34a;';
                                } elseif ((int)$contact->rating === 4) {
                                    $rowStyle = 'background-color:#f0fdf4;border-left:3px solid #86efac;';
                                } elseif ((int)$contact->rating === 3) {
                                    $rowStyle = 'background-color:#eff6ff;border-left:3px solid #3b82f6;';
                                } elseif ((int)$contact->rating === 2) {
                                    $rowStyle = 'background-color:#f0f9ff;border-left:3px solid #93c5fd;';
                                } elseif ((int)$contact->rating === 1) {
                                    $rowStyle = 'background-color:#fdf8f0;border-left:3px solid #92400e;';
                                } else {
                                    $rowStyle = '';
                                }
                            @endphp
                            <x-ui.table-row style="{{ $rowStyle }}">
                                @unless ($isClerk)
                                    <x-ui.table-cell>
                                        <input type="checkbox"
                                               data-contact-checkbox
                                               value="{{ $contact->id }}"
                                               :checked="isChecked({{ $contact->id }})"
                                               @change="toggle({{ $contact->id }}, $event.target.checked)"
                                               class="rounded border-input" />
                                    </x-ui.table-cell>
                                @endunless
                                <x-ui.table-cell>
                                    <a href="{{ route('contacts.show', $contact) }}" class="flex items-center gap-3 group">
                                        <x-ui.avatar :name="$contact->name" :src="$contact->photo" size="sm" />
                                        <div class="min-w-0">
                                            <div class="font-medium group-hover:underline truncate flex items-center gap-1.5">
                                                {{ $contact->name }}
                                                @if ($contact->status === 'banned')
                                                    <span style="font-size:10px;background:#fca5a5;color:#7f1d1d;padding:1px 5px;border-radius:4px;font-weight:600;">BANNED</span>
                                                @elseif ($contact->status === 'suspended')
                                                    <span style="font-size:10px;background:#fed7aa;color:#7c2d12;padding:1px 5px;border-radius:4px;font-weight:600;">SUSPENDED</span>
                                                @elseif ($contact->rating > 0)
                                                    <span style="font-size:11px;">
                                                        @for ($i = 1; $i <= 5; $i++)<span style="color:{{ $i <= (int)$contact->rating ? '#f59e0b' : '#d1d5db' }};">★</span>@endfor
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-muted-foreground truncate">
                                                {{ $contact->email ?: $contact->phone ?: '—' }}
                                            </div>
                                        </div>
                                    </a>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden md:table-cell">
                                    @if ($contact->company)
                                        <div class="text-sm">{{ $contact->company }}</div>
                                        @if ($contact->job_title)
                                            <div class="text-xs text-muted-foreground">{{ $contact->job_title }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted-foreground">—</span>
                                    @endif
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden lg:table-cell">
                                    @if ($contact->group)
                                        <span class="inline-flex items-center gap-1.5 text-sm">
                                            <span class="h-2 w-2 rounded-full" style="background:{{ $contact->group->color ?: '#a855f7' }}"></span>
                                            {{ $contact->group->name }}
                                        </span>
                                    @else
                                        <span class="text-muted-foreground">—</span>
                                    @endif
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden xl:table-cell">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($contact->tags->take(3) as $tag)
                                            <x-ui.badge variant="outline">{{ $tag->name }}</x-ui.badge>
                                        @empty
                                            <span class="text-muted-foreground">—</span>
                                        @endforelse
                                        @if ($contact->tags->count() > 3)
                                            <span class="text-xs text-muted-foreground self-center">+{{ $contact->tags->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden xl:table-cell text-sm text-muted-foreground">
                                    {{ $contact->owner?->name ?? '—' }}
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <x-ui.dropdown-menu align="end" width="w-44">
                                        <x-slot:trigger>
                                            <button class="rounded-md p-1.5 hover:bg-accent text-muted-foreground hover:text-foreground">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                            </button>
                                        </x-slot:trigger>
                                        <x-ui.dropdown-menu-item :href="route('contacts.show', $contact)">View</x-ui.dropdown-menu-item>
                                        @can('update', $contact)
                                            <x-ui.dropdown-menu-item :href="route('contacts.edit', $contact)">Edit</x-ui.dropdown-menu-item>
                                        @endcan
                                        @can('delete', $contact)
                                            <x-ui.dropdown-menu-separator />
                                            <form method="POST" action="{{ route('contacts.destroy', $contact) }}" onsubmit="return confirm('Move {{ addslashes($contact->name) }} to trash?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-ui.dropdown-menu-item as="button" type="submit" destructive>Move to trash</x-ui.dropdown-menu-item>
                                            </form>
                                        @endcan
                                    </x-ui.dropdown-menu>
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
</x-app-layout>
