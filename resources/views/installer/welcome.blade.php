<x-installer-layout :step="$step">
    <div class="text-center space-y-4 mb-10">
        <div class="inline-flex items-center gap-2 rounded-full border border-border bg-card/60 px-3 py-1 text-xs font-medium backdrop-blur">
            <span class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
            <span class="text-muted-foreground">Welcome to your new CRM</span>
        </div>
        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight bg-gradient-to-br from-foreground via-foreground to-foreground/60 bg-clip-text text-transparent">
            Let's get you set up.
        </h1>
        <p class="text-base text-muted-foreground max-w-lg mx-auto">
            5 quick steps to a working CRM. We'll handle the database, settings, and your admin account — you won't need to touch a config file.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <x-ui.card>
            <x-ui.card-content class="p-5">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary/10 text-primary mb-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="font-semibold mb-1">What you'll need</h3>
                <ul class="text-sm text-muted-foreground space-y-1">
                    <li>• A database (SQLite works without setup)</li>
                    <li>• MySQL/Postgres credentials if used</li>
                    <li>• An email + password for the admin</li>
                </ul>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-content class="p-5">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary/10 text-primary mb-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-semibold mb-1">It'll take 2 minutes</h3>
                <ul class="text-sm text-muted-foreground space-y-1">
                    <li>• Verify server requirements</li>
                    <li>• Configure + migrate the database</li>
                    <li>• Create your admin account</li>
                </ul>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <x-ui.card class="bg-primary/5 border-primary/20">
        <x-ui.card-content class="p-4 flex items-start gap-3 text-sm">
            <svg class="h-4 w-4 shrink-0 mt-0.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong class="block text-foreground mb-0.5">Heads up</strong>
                <span class="text-muted-foreground">Your Twilio and Anthropic API keys live inside the app — you'll add them later from <code class="font-mono text-xs bg-muted px-1.5 py-0.5 rounded">Settings → Integrations</code>. No need to touch the <code class="font-mono text-xs bg-muted px-1.5 py-0.5 rounded">.env</code> file.</span>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-8">
        <span class="text-xs text-muted-foreground">By continuing you agree to the Envato license bundled with this product.</span>
        <a href="{{ route('install.requirements') }}"
           class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-md text-base font-medium bg-gradient-to-br from-primary to-fuchsia-500 text-white shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transition-all">
            Let's go
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</x-installer-layout>
