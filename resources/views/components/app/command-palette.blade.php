@php
$navItems = [
    ['label' => 'Dashboard', 'route' => 'dashboard'],
    ['label' => 'Contacts',  'route' => 'contacts.index'],
    ['label' => 'New contact', 'route' => 'contacts.create'],
    ['label' => 'Import CSV', 'route' => 'imports.form'],
    ['label' => 'Messages',  'route' => 'sms.index'],
    ['label' => 'Calls',     'route' => 'calls.index'],
    ['label' => 'Email',     'route' => 'emails.index'],
    ['label' => 'Compose email', 'route' => 'emails.create'],
    ['label' => 'Settings',  'route' => 'settings.general'],
    ['label' => 'API tokens','route' => 'api-tokens.index'],
];
$nav = collect($navItems)->filter(fn ($i) => \Illuminate\Support\Facades\Route::has($i['route']))->values();
@endphp

<div x-data="{
    open: false,
    query: '',
    selected: 0,
    contacts: [],
    nav: @js($nav->map(fn ($i) => ['label' => $i['label'], 'href' => route($i['route'])])),
    autocompleteUrl: '{{ route('contacts.autocomplete') }}',
    smsUrlFor: id => '{{ url('/sms') }}/' + id,
    contactUrlFor: id => '{{ url('/contacts') }}/' + id,

    get items() {
        const q = this.query.toLowerCase().trim();
        const navMatches = q === ''
            ? this.nav
            : this.nav.filter(n => n.label.toLowerCase().includes(q));
        const contactItems = this.contacts.map(c => ({
            label: c.name,
            sub: c.email || c.phone || c.company || '',
            href: this.contactUrlFor(c.id),
            sms: c.phone ? this.smsUrlFor(c.id) : null,
        }));
        return [...contactItems, ...navMatches];
    },

    openPalette() {
        this.open = true;
        this.query = '';
        this.selected = 0;
        this.contacts = [];
        this.$nextTick(() => this.$refs.input?.focus());
    },
    close() { this.open = false; },

    async search() {
        if (this.query.trim().length < 1) { this.contacts = []; this.selected = 0; return; }
        try {
            const r = await fetch(this.autocompleteUrl + '?q=' + encodeURIComponent(this.query));
            this.contacts = await r.json();
            this.selected = 0;
        } catch (e) { this.contacts = []; }
    },

    move(d) {
        const max = this.items.length - 1;
        this.selected = Math.max(0, Math.min(max, this.selected + d));
        this.$nextTick(() => {
            this.$refs.list?.querySelector('[data-active=true]')?.scrollIntoView({ block: 'nearest' });
        });
    },

    activate() {
        const item = this.items[this.selected];
        if (item?.href) window.location = item.href;
    },
 }"
 x-on:keydown.window.prevent.meta.k="openPalette()"
 x-on:keydown.window.prevent.ctrl.k="openPalette()">

    {{-- Trigger hint button (also accessible via ⌘K) --}}
    <button @click="openPalette" type="button"
            class="hidden sm:inline-flex items-center gap-2 rounded-md border border-input bg-background h-8 px-2 text-xs text-muted-foreground hover:bg-accent transition-colors">
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
        <span>Search</span>
        <kbd class="font-mono text-[10px] rounded border bg-muted px-1">⌘K</kbd>
    </button>

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-start justify-center pt-[12vh] p-4"
             @keydown.escape.window="close"
             @keydown.arrow-down.prevent="move(1)"
             @keydown.arrow-up.prevent="move(-1)"
             @keydown.enter.prevent="activate">

            <div x-show="open" x-transition.opacity @click="close" class="fixed inset-0 bg-background/80 backdrop-blur-sm"></div>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full max-w-xl rounded-lg border bg-popover text-popover-foreground shadow-2xl overflow-hidden">

                <div class="flex items-center border-b px-3">
                    <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    <input x-ref="input" x-model="query" @input.debounce.250ms="search"
                           type="text"
                           placeholder="Search contacts or jump to a page..."
                           class="flex h-12 w-full bg-transparent px-3 text-sm outline-none placeholder:text-muted-foreground" />
                    <kbd class="hidden sm:inline-block font-mono text-[10px] rounded border bg-muted px-1.5 py-0.5">Esc</kbd>
                </div>

                <div x-ref="list" class="max-h-[60vh] overflow-y-auto p-1">
                    <template x-if="items.length === 0">
                        <p class="text-center text-sm text-muted-foreground py-6">No results.</p>
                    </template>
                    <template x-for="(item, i) in items" :key="i">
                        <a :href="item.href"
                           :data-active="i === selected"
                           @mouseenter="selected = i"
                           class="flex items-center gap-3 rounded-md px-3 py-2 text-sm cursor-pointer"
                           :class="i === selected ? 'bg-accent text-accent-foreground' : ''">
                            <div class="flex-1 min-w-0">
                                <div class="font-medium truncate" x-text="item.label"></div>
                                <div x-show="item.sub" class="text-xs text-muted-foreground truncate" x-text="item.sub"></div>
                            </div>
                            <template x-if="item.sms">
                                <a :href="item.sms" @click.stop class="text-xs text-muted-foreground hover:text-foreground">SMS</a>
                            </template>
                        </a>
                    </template>
                </div>

                <div class="border-t px-3 py-2 text-[10px] text-muted-foreground flex items-center gap-3">
                    <span><kbd class="font-mono rounded border bg-muted px-1">↑↓</kbd> nav</span>
                    <span><kbd class="font-mono rounded border bg-muted px-1">↵</kbd> open</span>
                    <span><kbd class="font-mono rounded border bg-muted px-1">esc</kbd> close</span>
                </div>
            </div>
        </div>
    </template>
</div>
