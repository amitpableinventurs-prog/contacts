<x-app-layout>
    <x-slot:header>Messages / {{ $contact->name }}</x-slot:header>

    <div class="max-w-3xl mx-auto space-y-4">
        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('sms.index') }}" class="rounded-md p-2 hover:bg-accent text-muted-foreground hover:text-foreground">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="sr-only">Back to messages</span>
            </a>
            <x-ui.avatar :name="$contact->name" :src="$contact->photo" />
            <div class="flex-1 min-w-0">
                <a href="{{ route('contacts.show', $contact) }}" class="font-medium hover:underline">{{ $contact->name }}</a>
                <div class="text-xs text-muted-foreground">{{ $contact->phone ?? 'No phone on file' }}</div>
            </div>
        </div>

        {{-- Thread --}}
        <x-ui.card class="overflow-hidden">
            <div class="flex flex-col gap-3 p-4 max-h-[60vh] overflow-y-auto">
                @forelse ($messages as $message)
                    @php $isOut = $message->direction === 'outbound'; $isWA = $message->channel === 'whatsapp'; @endphp
                    <div class="flex items-end gap-1 {{ $isOut ? 'justify-end' : 'justify-start' }}">
                        @if ($isOut && auth()->user()->hasRole(\App\Support\Roles::SUPER_ADMIN, \App\Support\Roles::ADMIN))
                            <form method="POST" action="{{ route('sms.destroy', $message) }}" onsubmit="return confirm('Delete this sent message? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md p-1 text-muted-foreground hover:text-destructive hover:bg-destructive/10" title="Delete message">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        @endif
                        <div class="max-w-[85%] sm:max-w-[75%]">
                            <div @class([
                                'rounded-2xl px-3 py-2 text-sm whitespace-pre-line',
                                'bg-primary text-primary-foreground' => $isOut && !$isWA,
                                'bg-success text-success-foreground' => $isOut && $isWA,
                                'bg-muted text-foreground' => !$isOut,
                            ])>
                                {{ $message->body }}
                            </div>
                            <div class="mt-1 px-1 text-[10px] text-muted-foreground flex items-center gap-1 {{ $isOut ? 'justify-end' : 'justify-start' }}">
                                @if ($isWA)
                                    <span>WhatsApp ·</span>
                                @endif
                                <span>{{ $message->sent_at?->format('M j, g:i a') }}</span>
                                @if ($isOut)
                                    <span>· {{ $message->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-muted-foreground py-8">No messages yet — send the first one below.</p>
                @endforelse
            </div>

            {{-- Composer --}}
            <form method="POST" action="{{ route('sms.store', $contact) }}" class="border-t p-3 space-y-2"
                  x-data="{
                    busy: false,
                    showLang: false,
                    lang: 'English',
                    body() { return this.$refs.body; },
                    async transform(url, payload) {
                        if (!this.body().value.trim()) return;
                        this.busy = true;
                        try {
                            const r = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify(payload),
                            });
                            const data = await r.json();
                            if (data.text) this.body().value = data.text;
                            window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: data.fake ? 'Updated (fake mode).' : 'Updated by Claude.' }}));
                        } finally { this.busy = false; }
                    },
                  }">
                @csrf
                <x-ui.textarea name="body" x-ref="body"
                               rows="2"
                               placeholder="{{ $contact->phone ? 'Type a message...' : 'Add a phone number to this contact first' }}"
                               required
                               :disabled="!$contact->phone"
                               class="resize-none">{{ old('body') }}</x-ui.textarea>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex rounded-md border border-input p-0.5 text-xs">
                        <label class="cursor-pointer">
                            <input type="radio" name="channel" value="sms" class="peer sr-only" checked />
                            <span class="inline-flex items-center gap-1 rounded px-2 py-1 peer-checked:bg-accent peer-checked:text-accent-foreground">SMS</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="channel" value="whatsapp" class="peer sr-only" />
                            <span class="inline-flex items-center gap-1 rounded px-2 py-1 peer-checked:bg-accent peer-checked:text-accent-foreground">WhatsApp</span>
                        </label>
                    </div>

                    {{-- AI assist --}}
                    <button type="button"
                            @click="transform('{{ route('messaging.spell-check') }}', { text: body().value })"
                            x-bind:disabled="busy"
                            title="Fix spelling and grammar (Claude)"
                            class="inline-flex items-center gap-1 h-7 px-2 rounded-md text-xs font-medium border border-input bg-background hover:bg-accent disabled:opacity-50">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Fix
                    </button>
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open" x-bind:disabled="busy" title="Translate"
                                class="inline-flex items-center gap-1 h-7 px-2 rounded-md text-xs font-medium border border-input bg-background hover:bg-accent disabled:opacity-50">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Translate
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute bottom-full mb-1 right-0 w-44 rounded-md border bg-popover shadow-lg p-1">
                            @foreach (['English', 'Spanish', 'French', 'German', 'Italian', 'Portuguese', 'Chinese', 'Japanese', 'Arabic'] as $lang)
                                <button type="button"
                                        @click="open = false; transform('{{ route('messaging.translate') }}', { text: body().value, language: '{{ $lang }}' })"
                                        class="block w-full text-left px-2 py-1.5 text-sm rounded-sm hover:bg-accent">{{ $lang }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex-1"></div>
                    <span x-show="busy" x-cloak class="text-xs text-muted-foreground">Working…</span>
                    <x-ui.button type="submit" :disabled="!$contact->phone">Send</x-ui.button>
                </div>
            </form>
            @error('body')
                <p class="px-4 pb-2 text-xs text-destructive">{{ $message }}</p>
            @enderror
        </x-ui.card>

        <div class="text-xs text-muted-foreground text-center">
            Webhook URL for Twilio inbound: <code class="font-mono">{{ route('webhooks.twilio.sms') }}</code>
        </div>
    </div>
</x-app-layout>
