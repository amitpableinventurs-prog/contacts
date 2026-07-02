<x-app-layout>
    <x-slot:header>Contacts / Import / Importing…</x-slot:header>

    <div class="max-w-2xl mx-auto space-y-4"
         x-data="importRunner('{{ route('imports.process', ['import' => $importId]) }}', '{{ route('contacts.index') }}')"
         x-init="tick()">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Importing contacts</h1>
            <p class="text-sm text-muted-foreground">Keep this tab open — large files are imported in small batches. You can watch the progress below.</p>
        </div>

        <x-ui.card>
            <x-ui.card-content class="p-6 space-y-4">
                <div>
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="font-medium" x-text="error ? 'Import paused' : (done ? 'Finished' : 'Importing…')"></span>
                        <span class="text-muted-foreground" x-text="percent + '%'"></span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-muted overflow-hidden">
                        <div class="h-full rounded-full bg-primary transition-all duration-300" :style="`width:${percent}%`"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                    <div class="rounded-md border p-3">
                        <div class="text-xs text-muted-foreground">Processed</div>
                        <div class="font-semibold tabular-nums" x-text="processed.toLocaleString()"></div>
                    </div>
                    <div class="rounded-md border p-3">
                        <div class="text-xs text-muted-foreground">Created</div>
                        <div class="font-semibold tabular-nums" x-text="created.toLocaleString()"></div>
                    </div>
                    <div class="rounded-md border p-3">
                        <div class="text-xs text-muted-foreground">Updated</div>
                        <div class="font-semibold tabular-nums" x-text="updated.toLocaleString()"></div>
                    </div>
                    <div class="rounded-md border p-3">
                        <div class="text-xs text-muted-foreground">Skipped</div>
                        <div class="font-semibold tabular-nums" x-text="skipped.toLocaleString()"></div>
                    </div>
                </div>

                <template x-if="error">
                    <div class="rounded-md border border-destructive/40 bg-destructive/5 p-3 text-sm space-y-2">
                        <p class="text-destructive" x-text="error"></p>
                        <x-ui.button type="button" size="sm" @click="error = null; tick()">Retry</x-ui.button>
                    </div>
                </template>

                <template x-if="done">
                    <p class="text-sm text-muted-foreground">Done — taking you back to your contacts…</p>
                </template>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <script>
        function importRunner(processUrl, contactsUrl) {
            return {
                percent: 0, processed: 0, created: 0, updated: 0, skipped: 0,
                done: false, error: null,

                async tick() {
                    if (this.done) return;
                    try {
                        const res = await fetch(processUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            throw new Error(data.error || `Import failed (HTTP ${res.status}).`);
                        }
                        this.percent   = data.percent;
                        this.processed = data.processed;
                        this.created   = data.created;
                        this.updated   = data.updated;
                        this.skipped   = data.skipped;
                        if (data.done) {
                            this.done = true;
                            setTimeout(() => window.location.href = contactsUrl, 800);
                            return;
                        }
                        // Small breather if another tick is still running.
                        setTimeout(() => this.tick(), data.locked ? 1500 : 50);
                    } catch (e) {
                        this.error = e.message || 'Network error — check your connection and retry.';
                    }
                },
            };
        }
    </script>
</x-app-layout>
