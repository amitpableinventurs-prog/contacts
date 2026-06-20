<x-app-layout>
    <x-slot:header>Email / New</x-slot:header>

    <div class="max-w-3xl mx-auto space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Compose email</h1>
            <p class="text-sm text-muted-foreground">Send a one-off email to a contact. Bulk send + templates land in a later phase.</p>
        </div>

        <form method="POST" action="{{ route('emails.store') }}">
            @csrf
            <x-ui.card>
                <x-ui.card-content class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <x-ui.label for="contact_search">Contact <span class="text-destructive">*</span></x-ui.label>
                        <div x-data="{
                            query: '{{ $contact?->name }}',
                            results: [],
                            selectedId: '{{ $contact?->id ?? '' }}',
                            selectedLabel: '{{ $contact?->name }}{{ $contact ? ' <'.$contact->email.'>' : '' }}',
                            async search() {
                                if (this.query.length < 2) { this.results = []; return; }
                                const r = await fetch('{{ route('contacts.autocomplete') }}?q=' + encodeURIComponent(this.query));
                                this.results = await r.json();
                            },
                            pick(c) {
                                this.selectedId = c.id;
                                this.selectedLabel = c.name + (c.email ? ' <' + c.email + '>' : '');
                                this.query = this.selectedLabel;
                                this.results = [];
                            },
                        }" class="relative">
                            <x-ui.input id="contact_search"
                                        x-model="query"
                                        @input.debounce.300ms="search"
                                        placeholder="Search contacts by name or email..." />
                            <input type="hidden" name="contact_id" :value="selectedId" />
                            <div x-show="results.length > 0"
                                 x-cloak
                                 class="absolute z-10 mt-1 w-full rounded-md border bg-popover shadow-lg max-h-60 overflow-auto">
                                <template x-for="c in results" :key="c.id">
                                    <button type="button" @click="pick(c)"
                                            class="block w-full text-left px-3 py-2 text-sm hover:bg-accent">
                                        <span class="font-medium" x-text="c.name"></span>
                                        <span class="text-muted-foreground text-xs" x-text="c.email || c.phone || ''"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        @error('contact_id') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <x-ui.label for="subject">Subject <span class="text-destructive">*</span></x-ui.label>
                        <x-ui.input id="subject" name="subject" value="{{ old('subject') }}" required />
                        @error('subject') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <x-ui.label for="body">Message <span class="text-destructive">*</span></x-ui.label>
                        <x-ui.textarea id="body" name="body" rows="10" required>{{ old('body') }}</x-ui.textarea>
                        @error('body') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('emails.index') }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent transition-colors focus-ring">Cancel</a>
                <x-ui.button type="submit">Send email</x-ui.button>
            </div>
        </form>
    </div>
</x-app-layout>
