<x-app-layout>
    <x-slot:header>Calls</x-slot:header>

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Calls</h1>
                <p class="text-sm text-muted-foreground">Click-to-call history and dialer.</p>
            </div>
        </div>

        @if (config('twilio.fake'))
            <x-ui.card class="border-warning/40 bg-warning/5">
                <x-ui.card-content class="p-4 flex items-start gap-3 text-sm">
                    <svg class="h-4 w-4 shrink-0 mt-0.5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/></svg>
                    <div class="space-y-1">
                        <p><span class="font-medium">Twilio fake mode</span> — browser calling requires real Twilio credentials, a Voice-enabled number, and a TwiML application SID. Set <code class="font-mono">TWILIO_SID</code>, <code class="font-mono">TWILIO_API_KEY</code>, <code class="font-mono">TWILIO_API_SECRET</code>, <code class="font-mono">TWILIO_APPLICATION_SID</code>, and <code class="font-mono">TWILIO_NUMBER</code> in <code class="font-mono">.env</code>.</p>
                        <p class="text-muted-foreground">The dialer button below records a placeholder call entry so you can see the call log working.</p>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Dialer --}}
            <x-ui.card class="lg:col-span-1">
                <x-ui.card-header>
                    <x-ui.card-title>Dialer</x-ui.card-title>
                    <x-ui.card-description>Place a call from the browser.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-3"
                    x-data="{
                        number: '',
                        append(d) { this.number += d; },
                        backspace() { this.number = this.number.slice(0, -1); },
                    }">
                    <input x-model="number"
                           placeholder="+1 415 555 0142"
                           class="flex h-12 w-full rounded-md border border-input bg-transparent px-3 text-center text-xl font-mono shadow-sm focus-ring" />
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['1','2','3','4','5','6','7','8','9','*','0','#'] as $digit)
                            <button type="button" @click="append('{{ $digit }}')"
                                    class="h-12 rounded-md border border-input bg-background text-base font-medium hover:bg-accent transition-colors focus-ring">
                                {{ $digit }}
                            </button>
                        @endforeach
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="backspace"
                                class="flex-1 h-10 rounded-md border border-input hover:bg-accent text-sm transition-colors">
                            ⌫
                        </button>
                        <button type="button"
                                @click="window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Twilio not configured. See note above.' }}))"
                                class="flex-1 h-10 rounded-md bg-success text-success-foreground text-sm font-medium hover:bg-success/90 transition-colors">
                            Call
                        </button>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            {{-- Recent calls --}}
            <x-ui.card class="lg:col-span-2 overflow-hidden">
                <x-ui.card-header>
                    <x-ui.card-title>Recent calls</x-ui.card-title>
                    <x-ui.card-description>Logged voice calls across the workspace.</x-ui.card-description>
                </x-ui.card-header>
                @if ($calls->isEmpty())
                    <x-ui.card-content>
                        <p class="text-sm text-muted-foreground text-center py-8">No calls logged yet.</p>
                    </x-ui.card-content>
                @else
                    <x-ui.table>
                        <x-ui.table-header>
                            <x-ui.table-row class="hover:bg-transparent">
                                <x-ui.table-head>Contact</x-ui.table-head>
                                <x-ui.table-head class="hidden sm:table-cell">Number</x-ui.table-head>
                                <x-ui.table-head>Status</x-ui.table-head>
                                <x-ui.table-head>When</x-ui.table-head>
                            </x-ui.table-row>
                        </x-ui.table-header>
                        <x-ui.table-body>
                            @foreach ($calls as $call)
                                <x-ui.table-row>
                                    <x-ui.table-cell>
                                        @if ($call->contact)
                                            <a href="{{ route('contacts.show', $call->contact) }}" class="flex items-center gap-2 group">
                                                <x-ui.avatar :name="$call->contact->name" size="sm" />
                                                <span class="font-medium group-hover:underline">{{ $call->contact->name }}</span>
                                            </a>
                                        @else
                                            <span class="text-muted-foreground">Unknown</span>
                                        @endif
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="hidden sm:table-cell font-mono text-sm">{{ $call->to_number }}</x-ui.table-cell>
                                    <x-ui.table-cell>
                                        <x-ui.badge variant="{{ $call->status === 'completed' ? 'success' : ($call->status === 'failed' ? 'destructive' : 'secondary') }}">
                                            {{ $call->status }}
                                        </x-ui.badge>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="text-sm text-muted-foreground">
                                        {{ $call->sent_at?->diffForHumans() }}
                                    </x-ui.table-cell>
                                </x-ui.table-row>
                            @endforeach
                        </x-ui.table-body>
                    </x-ui.table>
                    <div class="border-t p-3">
                        {{ $calls->links() }}
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
