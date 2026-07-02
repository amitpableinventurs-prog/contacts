<x-app-layout>
    <x-slot:header>Contacts / Import</x-slot:header>

    <div class="max-w-2xl mx-auto space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Import contacts</h1>
            <p class="text-sm text-muted-foreground">Upload a CSV or ZIP file. Next step lets you map columns and confirm.</p>
        </div>

        <form method="POST" action="{{ route('imports.preview') }}" enctype="multipart/form-data">
            @csrf
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>CSV or ZIP file</x-ui.card-title>
                    <x-ui.card-description>Upload a CSV file or a ZIP containing a CSV. Max 50MB — large files (lakhs of rows) are imported in batches with a progress bar.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <x-ui.input type="file" name="csv" accept=".csv,.zip,text/csv,application/zip" required />
                    @error('csv') <p class="text-xs text-destructive">{{ $message }}</p> @enderror

                    <div class="rounded-md border bg-muted/30 p-3 text-xs text-muted-foreground space-y-1">
                        <p class="font-medium text-foreground">Supported destination fields</p>
                        <p>{{ implode(' · ', array_keys($fields)) }}</p>
                        <p>Tags should be comma-separated in a single column.</p>
                    </div>
                </x-ui.card-content>
                <x-ui.card-footer class="justify-between">
                    <a href="{{ route('imports.template') }}" class="inline-flex items-center gap-2 h-9 rounded-md border border-input bg-background px-4 text-sm font-medium hover:bg-accent">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download template
                    </a>
                    <x-ui.button type="submit">Continue →</x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </form>
    </div>
</x-app-layout>
