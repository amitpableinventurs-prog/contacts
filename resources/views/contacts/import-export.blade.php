<x-app-layout>
    <x-slot:header>Contacts / Import &amp; Export</x-slot:header>

    <div class="space-y-4 max-w-2xl">
        <div>
            <a href="{{ route('contacts.index') }}" class="inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Contacts
            </a>
            <h1 class="text-2xl font-semibold tracking-tight mt-2">Import &amp; Export</h1>
            <p class="text-sm text-muted-foreground">Bring contacts in from a spreadsheet, or download the workspace.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @can('manage-imports')
                <x-ui.card>
                    <x-ui.card-content class="p-6 space-y-3">
                        <div class="h-9 w-9 rounded-md bg-muted grid place-items-center">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <div>
                            <h2 class="font-medium">Import contacts</h2>
                            <p class="text-sm text-muted-foreground">Upload a CSV or spreadsheet to add or update contacts in bulk.</p>
                        </div>
                        <a href="{{ route('imports.form') }}"><x-ui.button size="sm">Go to Import</x-ui.button></a>
                    </x-ui.card-content>
                </x-ui.card>
            @endcan

            @can('manage-export')
                <x-ui.card>
                    <x-ui.card-content class="p-6 space-y-3">
                        <div class="h-9 w-9 rounded-md bg-muted grid place-items-center">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </div>
                        <div>
                            <h2 class="font-medium">Export contacts</h2>
                            <p class="text-sm text-muted-foreground">Download the workspace's contacts, PIN-protected.</p>
                        </div>
                        <a href="{{ route('workspace.export') }}"><x-ui.button size="sm">Go to Export</x-ui.button></a>
                    </x-ui.card-content>
                </x-ui.card>
            @endcan
        </div>
    </div>
</x-app-layout>
