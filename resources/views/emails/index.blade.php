<x-app-layout>
    <x-slot:header>Email</x-slot:header>

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Email</h1>
                <p class="text-sm text-muted-foreground">Queued and sent emails across the workspace.</p>
            </div>
            <a href="{{ route('emails.create') }}">
                <x-ui.button>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Compose
                </x-ui.button>
            </a>
        </div>

        @if (config('mail.default') === 'log')
            <x-ui.card class="border-warning/40 bg-warning/5">
                <x-ui.card-content class="p-4 flex items-center gap-3 text-sm">
                    <svg class="h-4 w-4 shrink-0 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/></svg>
                    <div>
                        <span class="font-medium">Mail driver: log</span>
                        <span class="text-muted-foreground">— emails write to <code class="font-mono">storage/logs/laravel.log</code> instead of going out. Set MAIL_MAILER in .env to switch.</span>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        @endif

        <x-ui.card class="overflow-hidden">
            @if ($emails->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-muted grid place-items-center mb-3">
                        <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-base font-medium">No emails sent yet</h3>
                    <p class="text-sm text-muted-foreground mt-1 mb-4">Compose your first message.</p>
                    <a href="{{ route('emails.create') }}">
                        <x-ui.button>Compose</x-ui.button>
                    </a>
                </div>
            @else
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row class="hover:bg-transparent">
                            <x-ui.table-head>To</x-ui.table-head>
                            <x-ui.table-head>Subject</x-ui.table-head>
                            <x-ui.table-head>Status</x-ui.table-head>
                            <x-ui.table-head class="hidden lg:table-cell">Opens</x-ui.table-head>
                            <x-ui.table-head class="hidden md:table-cell">Sent</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @foreach ($emails as $email)
                            <x-ui.table-row>
                                <x-ui.table-cell>
                                    @if ($email->contact)
                                        <a href="{{ route('contacts.show', $email->contact) }}" class="flex items-center gap-2 group">
                                            <x-ui.avatar :name="$email->contact->name" size="sm" />
                                            <div>
                                                <div class="font-medium group-hover:underline truncate">{{ $email->contact->name }}</div>
                                                <div class="text-xs text-muted-foreground truncate">{{ $email->to_email }}</div>
                                            </div>
                                        </a>
                                    @else
                                        <span class="text-sm">{{ $email->to_email }}</span>
                                    @endif
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <div class="font-medium text-sm truncate max-w-md">{{ $email->subject }}</div>
                                    <div class="text-xs text-muted-foreground truncate max-w-md">{{ \Illuminate\Support\Str::limit(strip_tags($email->body_text ?? $email->body_html ?? ''), 80) }}</div>
                                </x-ui.table-cell>
                                <x-ui.table-cell>
                                    <x-ui.badge variant="{{ $email->status === 'sent' ? 'success' : ($email->status === 'failed' ? 'destructive' : 'secondary') }}">
                                        {{ $email->status }}
                                    </x-ui.badge>
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden lg:table-cell text-sm">
                                    @if ($email->opens_count > 0)
                                        <span class="inline-flex items-center gap-1 text-success">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            {{ $email->opens_count }}
                                        </span>
                                    @else
                                        <span class="text-muted-foreground">—</span>
                                    @endif
                                </x-ui.table-cell>
                                <x-ui.table-cell class="hidden md:table-cell text-sm text-muted-foreground">
                                    {{ $email->sent_at?->diffForHumans() ?? '—' }}
                                </x-ui.table-cell>
                            </x-ui.table-row>
                        @endforeach
                    </x-ui.table-body>
                </x-ui.table>
                <div class="border-t p-3">
                    {{ $emails->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
