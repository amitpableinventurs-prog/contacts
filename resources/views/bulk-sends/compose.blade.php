<x-app-layout :title="'Send to selection'">
    <div class="max-w-3xl mx-auto space-y-6 py-6 px-4 sm:px-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Send to selection</h1>
                <p class="text-sm text-muted-foreground mt-0.5">Compose one message that goes out to all selected contacts. Personalize with merge tokens.</p>
            </div>
            <a href="{{ route('contacts.index') }}" class="text-sm text-muted-foreground hover:text-foreground">← Back to contacts</a>
        </div>

        @if ($errors->any())
            <x-ui.card class="bg-destructive/5 border-destructive/30">
                <x-ui.card-content class="p-4 text-sm">
                    @foreach ($errors->all() as $err)
                        <div class="text-destructive">{{ $err }}</div>
                    @endforeach
                </x-ui.card-content>
            </x-ui.card>
        @endif

        <form method="POST" action="{{ route('bulk-sends.store') }}"
              x-data="{
                  channel: '{{ old('channel', $template?->channel ?? 'email') }}',
                  saveTemplate: false,
              }">
            @csrf
            @foreach ($contacts as $c)
                <input type="hidden" name="contact_ids[]" value="{{ $c->id }}">
            @endforeach

            {{-- Templates --}}
            @if ($templates->isNotEmpty() || $template)
                <x-ui.card class="mb-4">
                    <x-ui.card-content class="p-4 flex flex-wrap items-center gap-3">
                        <span class="text-sm font-medium">Templates</span>
                        @foreach ($templates as $t)
                            @php
                                $ids = $contacts->pluck('id')->join(',');
                                $href = route('bulk-sends.compose').'?contact_ids='.$ids.'&template_id='.$t->id;
                            @endphp
                            <a href="{{ $href }}"
                               class="inline-flex items-center gap-1.5 rounded-md border bg-card px-2 py-1 text-xs hover:bg-accent transition-colors {{ $template?->id === $t->id ? 'border-primary bg-primary/5' : '' }}">
                                <x-ui.badge variant="secondary" class="!py-0 !px-1 !text-[10px]">{{ strtoupper($t->channel) }}</x-ui.badge>
                                <span class="font-medium">{{ $t->name }}</span>
                            </a>
                        @endforeach
                        @if ($template)
                            <a href="{{ route('bulk-sends.compose') }}?contact_ids={{ $contacts->pluck('id')->join(',') }}"
                               class="text-xs text-muted-foreground hover:text-foreground ml-auto">
                                Clear template
                            </a>
                        @endif
                    </x-ui.card-content>
                </x-ui.card>
            @endif

            {{-- Recipients summary --}}
            <x-ui.card class="mb-4">
                <x-ui.card-header>
                    <x-ui.card-title class="flex items-center justify-between">
                        <span>Recipients</span>
                        <x-ui.badge variant="secondary">{{ $contacts->count() }} selected</x-ui.badge>
                    </x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($contacts->take(20) as $c)
                            <span class="inline-flex items-center gap-1.5 rounded-md border bg-card px-2 py-1 text-xs">
                                <span class="font-medium">{{ $c->name }}</span>
                                <span class="text-muted-foreground" x-show="channel === 'email'" x-cloak>· {{ $c->email ?: '—' }}</span>
                                <span class="text-muted-foreground" x-show="channel !== 'email'" x-cloak>· {{ $c->phone ?: '—' }}</span>
                            </span>
                        @endforeach
                        @if ($contacts->count() > 20)
                            <span class="text-xs text-muted-foreground self-center">+ {{ $contacts->count() - 20 }} more</span>
                        @endif
                    </div>
                    <p class="text-xs text-muted-foreground mt-3">Contacts without the required field for the chosen channel will be silently skipped.</p>
                </x-ui.card-content>
            </x-ui.card>

            {{-- Channel picker --}}
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Channel</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @php
                        $channels = [
                            ['key' => 'email',    'name' => 'Email',    'desc' => 'HTML or plain text'],
                            ['key' => 'sms',      'name' => 'SMS',      'desc' => 'Twilio SMS · 160 chars/seg'],
                            ['key' => 'whatsapp', 'name' => 'WhatsApp', 'desc' => 'Twilio WhatsApp business'],
                        ];
                        @endphp
                        @foreach ($channels as $ch)
                            <label class="cursor-pointer block">
                                <input type="radio" name="channel" value="{{ $ch['key'] }}" x-model="channel" class="peer sr-only" {{ ($template?->channel ?? old('channel', 'email')) === $ch['key'] ? 'checked' : '' }} />
                                <div class="rounded-lg border-2 border-border bg-card p-4 transition-all hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5">
                                    <div class="font-semibold text-sm mb-1">{{ $ch['name'] }}</div>
                                    <p class="text-xs text-muted-foreground">{{ $ch['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            {{-- Composer --}}
            <x-ui.card class="mt-4">
                <x-ui.card-header>
                    <x-ui.card-title>Message</x-ui.card-title>
                    <x-ui.card-description>
                        Use merge tokens to personalize: <code class="font-mono text-xs bg-muted px-1 rounded">@{{first_name}}</code>,
                        <code class="font-mono text-xs bg-muted px-1 rounded">@{{name}}</code>,
                        <code class="font-mono text-xs bg-muted px-1 rounded">@{{company}}</code>,
                        <code class="font-mono text-xs bg-muted px-1 rounded">@{{email}}</code>,
                        <code class="font-mono text-xs bg-muted px-1 rounded">@{{phone}}</code>.
                    </x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="space-y-1.5" x-show="channel === 'email'" x-cloak>
                        <x-ui.label for="subject">Subject</x-ui.label>
                        <x-ui.input id="subject" name="subject" value="{{ old('subject', $template?->subject) }}" placeholder="e.g. Quick note about your account" />
                    </div>

                    <div class="space-y-1.5">
                        <x-ui.label for="body">Body</x-ui.label>
                        <x-ui.textarea id="body" name="body" rows="8" required placeholder="Hi @{{first_name}},&#10;&#10;…">{{ old('body', $template?->body) }}</x-ui.textarea>
                        <p class="text-xs text-muted-foreground" x-show="channel !== 'email'" x-cloak>
                            SMS messages over 160 characters are split into multiple segments. WhatsApp doesn't have a hard length cap.
                        </p>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            {{-- Save as template --}}
            <x-ui.card class="mt-4">
                <x-ui.card-content class="p-4 space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="save_template" value="1" x-model="saveTemplate"
                               class="h-4 w-4 rounded border-input text-primary focus:ring-primary" />
                        <span class="text-sm font-medium">Save as template for next time</span>
                    </label>
                    <div x-show="saveTemplate" x-cloak class="space-y-1.5">
                        <x-ui.label for="template_name">Template name</x-ui.label>
                        <x-ui.input id="template_name" name="template_name" placeholder="e.g. Cold outreach intro" />
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('contacts.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Cancel</a>
                <x-ui.button type="submit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Send to {{ $contacts->count() }} contact{{ $contacts->count() === 1 ? '' : 's' }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-app-layout>
