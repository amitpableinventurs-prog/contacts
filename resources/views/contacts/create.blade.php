<x-app-layout>
    <x-slot:header>Contacts / New</x-slot:header>

    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Add contact</h1>
            <p class="text-sm text-muted-foreground">Fill out the basics, or paste a signature / LinkedIn blurb below to auto-fill.</p>
        </div>

        {{-- AI enrichment --}}
        <x-ui.card x-data="{
            text: '',
            loading: false,
            fake: false,
            shown: false,
            async suggest() {
                if (this.text.trim().length < 10) return;
                this.loading = true;
                try {
                    const r = await fetch('{{ route('contacts.enrich') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ text: this.text }),
                    });
                    const data = await r.json();
                    this.fake = data.fake;
                    const f = data.fields || {};
                    const setField = (id, val) => { const el = document.getElementById(id); if (el && val) el.value = val; };
                    setField('name', f.name);
                    setField('email', f.email);
                    setField('phone', f.phone);
                    setField('company', f.company);
                    setField('job_title', f.job_title);
                    setField('website', f.website);
                    setField('linkedin', f.linkedin);
                    this.shown = true;
                    window.dispatchEvent(new CustomEvent('toast', { detail: {
                        type: 'success',
                        message: data.fake ? 'Fields extracted (regex fallback — set ANTHROPIC_API_KEY for AI).' : 'Fields extracted with Claude.',
                    }}));
                } finally {
                    this.loading = false;
                }
            },
         }">
            <x-ui.card-header>
                <x-ui.card-title class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    AI-assisted fill
                </x-ui.card-title>
                <x-ui.card-description>Paste an email signature, LinkedIn snippet, or business card text. Fields below get pre-filled — you can edit them before saving.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-3">
                <x-ui.textarea x-model="text" rows="4" placeholder="Jane Doe&#10;Head of Growth at Acme Inc&#10;jane@acme.com · +1 415 555 0142&#10;https://linkedin.com/in/janedoe" />
                <div class="flex items-center gap-2">
                    <x-ui.button type="button" @click="suggest" x-bind:disabled="loading || text.trim().length < 10">
                        <span x-show="!loading">Suggest fields</span>
                        <span x-show="loading" x-cloak>Working…</span>
                    </x-ui.button>
                    <span x-show="shown" x-cloak class="text-xs text-muted-foreground" x-text="fake ? '(fake mode — regex fallback)' : '(AI-generated)'"></span>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <form action="{{ route('contacts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('contacts._form', ['contact' => null, 'groups' => $groups, 'tags' => $tags])
        </form>
    </div>
</x-app-layout>
