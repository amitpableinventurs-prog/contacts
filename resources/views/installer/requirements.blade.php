<x-installer-layout :step="$step">
    @php
        $passCount = count(array_filter($checks));
        $total = count($checks);
    @endphp

    <div class="text-center space-y-3 mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Server requirements</h1>
        <p class="text-base text-muted-foreground">
            @if ($allPass)
                Everything looks good. <span class="text-success font-medium">{{ $passCount }} / {{ $total }} checks passed.</span>
            @else
                Fix the failing items below and re-check. <span class="text-destructive font-medium">{{ $total - $passCount }} need attention.</span>
            @endif
        </p>
    </div>

    @if ($allPass)
        <x-ui.card class="bg-success/5 border-success/30 mb-6">
            <x-ui.card-content class="p-4 flex items-center gap-3">
                <div class="grid h-10 w-10 place-items-center rounded-full bg-success text-success-foreground shrink-0 shadow-md shadow-success/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <strong class="block text-foreground">Your server is ready</strong>
                    <span class="text-sm text-muted-foreground">PHP version, extensions, and folder permissions all check out.</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    @else
        <x-ui.card class="bg-destructive/5 border-destructive/30 mb-6">
            <x-ui.card-content class="p-4 flex items-start gap-3">
                <svg class="h-5 w-5 mt-0.5 text-destructive shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/></svg>
                <div class="text-sm">
                    <strong class="block text-foreground mb-1">Action needed</strong>
                    <span class="text-muted-foreground">Install the missing PHP extensions (e.g. <code class="font-mono text-xs bg-muted px-1 rounded">sudo apt install php8.3-mbstring</code>) or fix permissions (<code class="font-mono text-xs bg-muted px-1 rounded">chmod -R u+rwX storage bootstrap/cache</code>), then refresh.</span>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    @endif

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <ul class="divide-y divide-border">
                @foreach ($checks as $label => $ok)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <div @class([
                            'grid h-7 w-7 place-items-center rounded-full shrink-0',
                            'bg-success/15 text-success' => $ok,
                            'bg-destructive/15 text-destructive' => !$ok,
                        ])>
                            @if ($ok)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </div>
                        <span class="text-sm font-medium flex-1">{{ $label }}</span>
                        <x-ui.badge variant="{{ $ok ? 'success' : 'destructive' }}">{{ $ok ? 'PASS' : 'FAIL' }}</x-ui.badge>
                    </li>
                @endforeach
            </ul>
        </x-ui.card-content>
    </x-ui.card>

    <div class="flex items-center justify-between pt-8">
        <a href="{{ route('install.welcome') }}"
           class="inline-flex h-9 items-center rounded-md border border-input bg-background px-4 text-sm font-medium hover:bg-accent transition-colors">
            ← Back
        </a>
        @if ($allPass)
            <a href="{{ route('install.database') }}"
               class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-md text-base font-medium bg-gradient-to-br from-primary to-fuchsia-500 text-white shadow-lg shadow-primary/30 hover:shadow-xl transition-all">
                Continue
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        @else
            <a href="{{ route('install.requirements') }}"
               class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-input bg-background px-6 text-base font-medium hover:bg-accent transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Re-check
            </a>
        @endif
    </div>
</x-installer-layout>
