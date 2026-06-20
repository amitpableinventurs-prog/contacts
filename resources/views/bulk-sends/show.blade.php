<x-app-layout :title="'Bulk send'">
    @php $status = $bulkSend->status(); @endphp

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 space-y-6"
         @if ($status === 'running')
             x-data x-init="setTimeout(() => window.location.reload(), 4000)"
         @endif
    >
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('bulk-sends.index') }}" class="text-sm text-muted-foreground hover:text-foreground">← All bulk sends</a>
                <h1 class="text-2xl font-bold tracking-tight mt-1">
                    {{ strtoupper($bulkSend->channel) }} · {{ $bulkSend->subject ?: \Illuminate\Support\Str::limit($bulkSend->body, 60) }}
                </h1>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Sent by {{ $bulkSend->user?->name ?: '—' }} · {{ $bulkSend->created_at->diffForHumans() }}
                </p>
            </div>
            <x-ui.badge variant="{{ $status === 'completed' ? 'success' : ($status === 'running' ? 'default' : 'secondary') }}" class="text-sm px-3 py-1">
                {{ $status }}
            </x-ui.badge>
        </div>

        {{-- Progress card --}}
        <x-ui.card>
            <x-ui.card-content class="p-6">
                <div class="flex items-end justify-between mb-3">
                    <div>
                        <div class="text-3xl font-bold tabular-nums">{{ $bulkSend->sent_count + $bulkSend->failed_count }} <span class="text-muted-foreground text-base font-normal">/ {{ $bulkSend->total_count }}</span></div>
                        <div class="text-sm text-muted-foreground mt-0.5">processed</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm">
                            <span class="text-success font-medium">{{ $bulkSend->sent_count }}</span> sent
                            @if ($bulkSend->failed_count > 0)
                                · <span class="text-destructive font-medium">{{ $bulkSend->failed_count }}</span> failed
                            @endif
                        </div>
                        <div class="text-xs text-muted-foreground mt-0.5">
                            @if ($bulkSend->finished_at)
                                Finished {{ $bulkSend->finished_at->diffForHumans() }}
                            @elseif ($bulkSend->started_at)
                                Started {{ $bulkSend->started_at->diffForHumans() }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="h-2 rounded-full bg-muted overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary to-fuchsia-500 transition-all" style="width: {{ $bulkSend->progress() }}%"></div>
                </div>
                @if ($status === 'running')
                    <p class="text-xs text-muted-foreground mt-2">Refreshing every 4 seconds…</p>
                @endif
            </x-ui.card-content>
        </x-ui.card>

        {{-- Message preview --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Message template</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-3">
                @if ($bulkSend->subject)
                    <div>
                        <div class="text-xs uppercase tracking-wider text-muted-foreground mb-1">Subject</div>
                        <div class="text-sm font-medium">{{ $bulkSend->subject }}</div>
                    </div>
                @endif
                <div>
                    <div class="text-xs uppercase tracking-wider text-muted-foreground mb-1">Body</div>
                    <pre class="text-sm whitespace-pre-wrap font-sans bg-muted/30 border rounded-md p-3">{{ $bulkSend->body }}</pre>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Recipients --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Recipients ({{ $contacts->count() }})</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="p-0">
                <ul class="divide-y divide-border">
                    @foreach ($contacts as $c)
                        <li class="flex items-center gap-3 px-5 py-2.5 text-sm">
                            <x-ui.avatar :name="$c->name" size="sm" />
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('contacts.show', $c) }}" class="font-medium hover:underline truncate block">{{ $c->name }}</a>
                                <div class="text-xs text-muted-foreground truncate">
                                    {{ $bulkSend->channel === 'email' ? ($c->email ?: 'no email') : ($c->phone ?: 'no phone') }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-app-layout>
