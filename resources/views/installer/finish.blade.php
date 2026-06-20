<x-installer-layout :step="$step">
    <div class="text-center space-y-6 mb-8">
        <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-success to-emerald-400 text-white shadow-xl shadow-success/40">
            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="space-y-3">
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight bg-gradient-to-br from-foreground via-foreground to-foreground/60 bg-clip-text text-transparent">
                You're all set! 🎉
            </h1>
            <p class="text-base text-muted-foreground max-w-md mx-auto">
                Contacts is installed and the installer has locked itself. Sign in with <code class="font-mono text-sm bg-muted px-1.5 py-0.5 rounded text-foreground">{{ $email }}</code> to get started.
            </p>
        </div>
    </div>

    @if (! empty($seeded) && ($seeded['contacts'] ?? 0) > 0)
        <x-ui.card class="bg-success/5 border-success/30 mb-4">
            <x-ui.card-content class="p-4 flex items-start gap-3 text-sm">
                <svg class="h-5 w-5 mt-0.5 text-success shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <div>
                    <strong class="block text-foreground mb-0.5">Demo data loaded</strong>
                    <span class="text-muted-foreground">
                        Added {{ $seeded['contacts'] }} contact{{ $seeded['contacts'] === 1 ? '' : 's' }},
                        {{ $seeded['groups'] }} group{{ $seeded['groups'] === 1 ? '' : 's' }},
                        {{ $seeded['tags'] }} tag{{ $seeded['tags'] === 1 ? '' : 's' }},
                        and {{ $seeded['reminders'] }} reminder{{ $seeded['reminders'] === 1 ? '' : 's' }}.
                        Delete them from inside the app whenever you're ready to start fresh.
                    </span>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    @endif

    <x-ui.card class="mb-4">
        <x-ui.card-header>
            <x-ui.card-title class="flex items-center gap-2">
                <span class="text-xl">🚀</span> Recommended next steps
            </x-ui.card-title>
            <x-ui.card-description>Quick wins to make Contacts fully yours.</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content class="p-0">
            <ul class="divide-y divide-border">
                @php
                $steps = [
                    ['n' => 1, 'title' => 'Connect Twilio',         'desc' => 'Real SMS, WhatsApp, and click-to-call. Drop your Account SID, Auth Token, and number into Settings → Twilio.'],
                    ['n' => 2, 'title' => 'Add your Anthropic key', 'desc' => 'Unlock real AI for contact enrichment, spell-check, and translation.'],
                    ['n' => 3, 'title' => 'Set up email',           'desc' => 'Pick a mail driver (SMTP, Resend, Mailgun, Postmark, SES) so outbound email actually sends.'],
                    ['n' => 4, 'title' => 'Invite your team',       'desc' => 'Workspace → Members → Send invite. Public registration is on by default — turn it off for invite-only.'],
                ];
                @endphp
                @foreach ($steps as $s)
                    <li class="flex items-start gap-3 px-5 py-4">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-primary/10 text-primary text-xs font-bold shrink-0">{{ $s['n'] }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm">{{ $s['title'] }}</div>
                            <div class="text-xs text-muted-foreground mt-0.5">{{ $s['desc'] }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </x-ui.card-content>
    </x-ui.card>

    <x-ui.card class="bg-primary/5 border-primary/20 mb-8">
        <x-ui.card-content class="p-4 flex items-start gap-3 text-sm">
            <svg class="h-4 w-4 mt-0.5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong class="block text-foreground mb-0.5">Production checklist</strong>
                <span class="text-muted-foreground">Run a queue worker (<code class="font-mono text-xs bg-muted px-1 rounded">php artisan queue:work</code>) and the scheduler (<code class="font-mono text-xs bg-muted px-1 rounded">php artisan schedule:work</code>) under a process supervisor. See <a href="/Documentation/index.html" class="text-primary hover:underline">the deployment guide</a>.</span>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <div class="flex items-center justify-center pt-2">
        <a href="{{ route('login') }}"
           class="inline-flex items-center justify-center gap-2 h-12 px-8 rounded-md text-base font-medium bg-gradient-to-br from-primary to-fuchsia-500 text-white shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transition-all">
            Go to sign in
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>

    <p class="text-xs text-muted-foreground text-center mt-8">
        To re-run the installer, delete <code class="font-mono">storage/app/installed.lock</code>.
    </p>
</x-installer-layout>
