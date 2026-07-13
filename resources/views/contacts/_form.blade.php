@props(['contact' => null, 'groups' => collect(), 'tags' => collect()])

@php
$selectedTagIds = $contact?->tags->pluck('id')->all() ?? old('tags', []);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Primary fields --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Basic info --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Basic information</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">

                {{-- Photo upload --}}
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <x-ui.avatar :name="$contact?->name ?? 'New'" :src="$contact?->photo ? asset('storage/'.$contact->photo) : null" size="lg" />
                    </div>
                    <div class="space-y-1 flex-1 min-w-0">
                        <x-ui.label for="photo">Profile photo (DP)</x-ui.label>
                        <input id="photo" name="photo" type="file" accept="image/*"
                               class="block w-full max-w-full text-sm text-muted-foreground file:mr-3 file:rounded-md file:border file:border-input file:bg-background file:px-3 file:py-1 file:text-sm file:font-medium file:cursor-pointer hover:file:bg-accent" />
                        <p class="text-xs text-muted-foreground">JPG, PNG up to 2 MB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5 sm:col-span-2">
                        <x-ui.label for="phone">Phone / Number <span class="text-destructive">*</span></x-ui.label>
                        <input type="hidden" id="phone_country" name="phone_country" value="{{ old('phone_country', $contact?->phone_country ?: 'in') }}" />
                        <x-ui.input id="phone" name="phone" value="{{ old('phone', $contact?->phone ?: $contact?->number) }}" placeholder="98765 43210" required autofocus />
                        @error('phone') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <x-ui.label for="name">Name <span class="text-destructive">*</span></x-ui.label>
                        <x-ui.input id="name" name="name" value="{{ old('name', $contact?->name) }}" required />
                        @error('name') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <x-ui.label for="email">Email</x-ui.label>
                        <x-ui.input id="email" name="email" type="email" value="{{ old('email', $contact?->email) }}" />
                        @error('email') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <x-ui.label for="city">City</x-ui.label>
                        <x-ui.input id="city" name="city" value="{{ old('city', $contact?->city) }}" />
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <x-ui.label for="address">Address</x-ui.label>
                        <x-ui.textarea id="address" name="address" rows="2">{{ old('address', $contact?->address) }}</x-ui.textarea>
                    </div>

                    {{-- Comment: every role sees it, only Super Admin can edit --}}
                    @if (auth()->user()->isSuperAdmin())
                        <div class="space-y-1.5 sm:col-span-2">
                            <x-ui.label for="admin_comment">Comment <span class="text-xs font-normal text-muted-foreground">(visible to all roles — only Super Admin can edit, max 100 characters)</span></x-ui.label>
                            <x-ui.textarea id="admin_comment" name="admin_comment" rows="2" maxlength="100">{{ old('admin_comment', $contact?->admin_comment) }}</x-ui.textarea>
                            @error('admin_comment') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>
                    @elseif ($contact?->admin_comment)
                        <div class="space-y-1.5 sm:col-span-2">
                            <x-ui.label>Comment <span class="text-xs font-normal text-muted-foreground">(only Super Admin can edit)</span></x-ui.label>
                            <p class="rounded-md border border-input bg-muted/30 px-3 py-2 text-sm whitespace-pre-line">{{ $contact->admin_comment }}</p>
                        </div>
                    @endif
                </div>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Description (HTML editor) --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Description</x-ui.card-title>
                <x-ui.card-description>Rich-text description for this contact.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content x-data="htmlEditor('description_html')">
                {{-- Toolbar --}}
                <div class="flex flex-wrap gap-1 mb-2 p-1 rounded-md border border-input bg-muted/30">
                    <button type="button" @click="exec('bold')" title="Bold" class="h-7 w-7 grid place-items-center rounded text-sm font-bold hover:bg-accent">B</button>
                    <button type="button" @click="exec('italic')" title="Italic" class="h-7 w-7 grid place-items-center rounded text-sm italic hover:bg-accent">I</button>
                    <button type="button" @click="exec('underline')" title="Underline" class="h-7 w-7 grid place-items-center rounded text-sm underline hover:bg-accent">U</button>
                    <div class="w-px bg-border mx-1"></div>
                    <button type="button" @click="exec('insertUnorderedList')" title="Bullet list" class="h-7 w-7 grid place-items-center rounded hover:bg-accent text-xs">• —</button>
                    <button type="button" @click="exec('insertOrderedList')" title="Numbered list" class="h-7 w-7 grid place-items-center rounded hover:bg-accent text-xs">1.</button>
                    <div class="w-px bg-border mx-1"></div>
                    <button type="button" @click="exec('removeFormat')" title="Clear formatting" class="h-7 px-2 rounded hover:bg-accent text-xs text-muted-foreground">Clear</button>
                </div>
                {{-- Editor --}}
                <div x-ref="editor"
                     contenteditable="true"
                     @input="sync"
                     class="min-h-[140px] rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-ring prose prose-sm max-w-none"
                >{!! old('description_html', $contact?->description_html) !!}</div>
                <input type="hidden" name="description_html" x-ref="hidden" value="{{ old('description_html', $contact?->description_html) }}" />
            </x-ui.card-content>
        </x-ui.card>

        {{-- Notes (plain) --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Quick notes</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <x-ui.textarea id="notes" name="notes" rows="4" placeholder="Short notes to remember...">{{ old('notes', $contact ? ($contact->getAttributes()['notes'] ?? '') : '') }}</x-ui.textarea>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Custom fields --}}
        @php
            $initialCustom = old('custom_fields_keys')
                ? collect(old('custom_fields_keys'))->map(fn ($k, $i) => ['key' => $k, 'value' => old('custom_fields_values')[$i] ?? ''])->all()
                : collect($contact?->custom_fields ?? [])->map(fn ($v, $k) => ['key' => (string) $k, 'value' => is_scalar($v) ? (string) $v : json_encode($v)])->values()->all();
        @endphp
        <div x-data='{
            rows: @json($initialCustom),
            add() { this.rows.push({ key: "", value: "" }); this.$nextTick(() => this.$refs.list?.lastElementChild?.querySelector("input")?.focus()); },
            remove(i) { this.rows.splice(i, 1); }
        }'>
            <x-ui.card>
                <x-ui.card-header class="flex flex-row items-center justify-between">
                    <div>
                        <x-ui.card-title>Custom fields</x-ui.card-title>
                        <x-ui.card-description>Extra data that doesn't fit standard fields.</x-ui.card-description>
                    </div>
                    <button type="button" @click="add" class="inline-flex items-center gap-1 h-7 px-2 rounded-md text-xs font-medium border border-input bg-background hover:bg-accent">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add field
                    </button>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div x-show="rows.length === 0" x-cloak class="text-sm text-muted-foreground text-center py-4">No custom fields.</div>
                    <div x-ref="list" class="space-y-2">
                        <template x-for="(row, i) in rows" :key="i">
                            <div class="flex flex-wrap gap-2 items-start">
                                <input type="text" name="custom_fields_keys[]" x-model="row.key" placeholder="Label"
                                       class="flex-1 min-w-[140px] h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring" />
                                <input type="text" name="custom_fields_values[]" x-model="row.value" placeholder="Value"
                                       class="flex-[2] min-w-[180px] h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring" />
                                <button type="button" @click="remove(i)" class="h-9 w-9 grid place-items-center rounded-md border border-input text-muted-foreground hover:text-destructive hover:border-destructive">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        {{-- Social --}}
        <x-ui.card x-data="{ open: {{ $contact && ($contact->facebook || $contact->twitter || $contact->linkedin) ? 'true' : 'false' }} }">
            <x-ui.card-header class="cursor-pointer select-none flex flex-row items-center justify-between" @click="open = !open">
                <div>
                    <x-ui.card-title>Social media</x-ui.card-title>
                    <x-ui.card-description>Optional links and handles.</x-ui.card-description>
                </div>
                <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </x-ui.card-header>
            <x-ui.card-content x-show="open" x-transition class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <x-ui.label for="twitter">X / Twitter</x-ui.label>
                    <x-ui.input id="twitter" name="twitter" value="{{ old('twitter', $contact?->twitter) }}" placeholder="@username" />
                </div>
                <div class="space-y-1.5">
                    <x-ui.label for="linkedin">LinkedIn</x-ui.label>
                    <x-ui.input id="linkedin" name="linkedin" value="{{ old('linkedin', $contact?->linkedin) }}" placeholder="username" />
                </div>
                <div class="space-y-1.5">
                    <x-ui.label for="facebook">Facebook</x-ui.label>
                    <x-ui.input id="facebook" name="facebook" value="{{ old('facebook', $contact?->facebook) }}" placeholder="username" />
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    {{-- Side metadata --}}
    <div class="space-y-4">

        {{-- Star rating --}}
        <x-ui.card x-data="{
            rating: {{ old('rating', $contact?->rating ?? 0) }},
            hover: 0,
            set(v) { this.rating = v; document.getElementById('rating-input').value = v; }
        }">
            <x-ui.card-header>
                <x-ui.card-title>Rating</x-ui.card-title>
                <x-ui.card-description>Rate this contact out of 5.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="flex items-center gap-1">
                    <template x-for="i in 5" :key="i">
                        <button type="button"
                                @click="set(i)"
                                @mouseenter="hover = i"
                                @mouseleave="hover = 0"
                                class="h-8 w-8 grid place-items-center transition-colors">
                            <svg class="h-6 w-6 transition-colors"
                                 :style="(hover || rating) >= i ? 'color:#f59e0b;fill:#f59e0b' : 'color:#d1d5db;fill:transparent'"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </button>
                    </template>
                    <span class="ml-2 text-sm text-muted-foreground" x-text="rating ? rating + '/5' : 'Not rated'"></span>
                </div>
                <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', $contact?->rating ?? 0) }}" />
            </x-ui.card-content>
        </x-ui.card>

        {{-- Organize --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Organize</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                <div class="space-y-1.5">
                    <x-ui.label for="group_id">Group</x-ui.label>
                    <select id="group_id" name="group_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                        <option value="">No group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" @selected(old('group_id', $contact?->group_id) == $group->id)>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Tags with AI suggestion --}}
        <x-ui.card x-data="{
            busy: false, suggested: [], shown: false, fake: false,
            async suggest() {
                const text = (document.getElementById('notes')?.value || '') + '\n' + (document.querySelector('[name=company]')?.value || '') + '\n' + (document.querySelector('[name=job_title]')?.value || '');
                if (text.trim().length < 5) { window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Add notes, company, or job title first.' }})); return; }
                this.busy = true;
                try {
                    const r = await fetch('{{ route('contacts.suggest-tags') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ text }) });
                    const data = await r.json();
                    this.suggested = (data.tags || []).map(t => String(t.id));
                    this.fake = data.fake; this.shown = true;
                    if (!this.suggested.length) window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'No relevant tags found.' }}));
                } finally { this.busy = false; }
            },
            apply() { this.suggested.forEach(id => { const cb = document.querySelector(`input[name='tags[]'][value='${id}']`); if (cb && !cb.checked) cb.checked = true; }); window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: 'Suggested tags applied.' }})); },
            isSuggested(id) { return this.suggested.includes(String(id)); }
        }">
            <x-ui.card-header class="flex flex-row items-center justify-between">
                <x-ui.card-title>Tags</x-ui.card-title>
                @if ($tags->isNotEmpty())
                    <button type="button" @click="suggest" :disabled="busy" class="inline-flex items-center gap-1 h-7 px-2 rounded-md text-xs font-medium border border-input bg-background hover:bg-accent disabled:opacity-50">
                        <svg class="h-3 w-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span x-show="!busy">AI suggest</span>
                        <span x-show="busy" x-cloak>Thinking…</span>
                    </button>
                @endif
            </x-ui.card-header>
            <x-ui.card-content>
                @if ($tags->isEmpty())
                    <p class="text-sm text-muted-foreground">No tags yet. <a href="{{ route('tags.index') }}" class="underline">Create some</a> first.</p>
                @else
                    <div x-show="shown && suggested.length > 0" x-cloak class="mb-3 rounded-md border border-primary/30 bg-primary/5 p-2.5">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium text-primary" x-text="fake ? 'Suggested (regex)' : '✨ Claude suggests'"></span>
                            <button type="button" @click="apply" class="text-xs text-primary hover:underline">Apply all</button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            <label class="cursor-pointer relative">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer sr-only" @checked(in_array($tag->id, $selectedTagIds)) />
                                <span :class="isSuggested({{ $tag->id }}) ? 'ring-2 ring-primary/40 ring-offset-1' : ''"
                                      class="inline-flex items-center rounded-md border border-input bg-background px-2.5 py-1 text-xs font-medium transition-all hover:bg-accent peer-checked:bg-primary peer-checked:text-primary-foreground peer-checked:border-primary">
                                    {{ $tag->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a href="{{ url()->previous() }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm transition-colors hover:bg-accent focus-ring">Cancel</a>
    <x-ui.button type="submit">{{ $contact ? 'Save changes' : 'Create contact' }}</x-ui.button>
</div>

@push('head')
<link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/css/intlTelInput.min.css') }}" />
<style>
    .iti { width: 100%; }
    .iti__dropdown-content { background-color: hsl(var(--card)); border: 1px solid hsl(var(--border)); color: hsl(var(--foreground)); }
    .iti__country--highlight, .iti__country:hover { background-color: hsl(var(--accent)); }
    .iti__search-input { background-color: transparent; color: hsl(var(--foreground)); }
    .iti__dial-code { color: hsl(var(--muted-foreground)); }
</style>
@endpush

@push('scripts')
<script>
function htmlEditor(fieldName) {
    return {
        init() {
            this.$refs.editor.innerHTML = this.$refs.hidden.value || '';
        },
        exec(cmd) {
            document.execCommand(cmd, false, null);
            this.$refs.editor.focus();
            this.sync();
        },
        sync() {
            this.$refs.hidden.value = this.$refs.editor.innerHTML;
        }
    };
}
</script>
<script src="{{ asset('vendor/intl-tel-input/js/intlTelInputWithUtils.min.js') }}"></script>
<script>
(function () {
    const input = document.getElementById('phone');
    const countryField = document.getElementById('phone_country');
    if (!input || !countryField || !window.intlTelInput) return;

    const iti = window.intlTelInput(input, {
        initialCountry: countryField.value || 'in',
        separateDialCode: true,
        countryOrder: ['in', 'us', 'gb', 'ae', 'sa', 'sg'],
    });

    const syncCountry = function () {
        const c = iti.getSelectedCountry();
        if (c && c.iso2) countryField.value = c.iso2;
    };
    input.addEventListener('countrychange', syncCountry);
    // A legacy "+xx…" value can override the initial country during init.
    syncCountry();

    // Store bare digits — spaces from as-you-type formatting would break
    // the LIKE-based phone search.
    const form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            input.value = input.value.replace(/[^0-9]/g, '');
        });
    }
})();
</script>
@endpush
