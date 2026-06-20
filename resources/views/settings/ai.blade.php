<x-app-layout>
    <x-slot:header>Settings / AI</x-slot:header>

    <div class="max-w-5xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Settings</h1>
            <p class="text-sm text-muted-foreground">Anthropic Claude powers contact enrichment, spell-check, and translation.</p>
        </div>

        <x-settings-layout active="ai">
            <form method="POST" action="{{ route('settings.ai.update') }}">
                @csrf
                @method('PATCH')

                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Anthropic Claude</x-ui.card-title>
                        <x-ui.card-description>Get an API key at <a href="https://console.anthropic.com" target="_blank" rel="noopener" class="underline">console.anthropic.com</a>.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">

                        <label class="flex items-start gap-3 rounded-md border border-input p-3 cursor-pointer">
                            <input type="checkbox" name="fake_mode" value="1" @checked(old('fake_mode', $settings->fake_mode)) class="mt-0.5 rounded border-input" />
                            <div>
                                <div class="text-sm font-medium">Fake mode</div>
                                <div class="text-xs text-muted-foreground">Use regex/heuristic fallbacks instead of calling the API. Auto-enables when no API key is set.</div>
                            </div>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5 sm:col-span-2">
                                <x-ui.label for="api_key">API key</x-ui.label>
                                <x-ui.input id="api_key" name="api_key" type="password" placeholder="{{ $settings->api_key ? '••••••••••••• (set, leave blank to keep)' : 'sk-ant-…' }}" />
                                @error('api_key') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="model">Model</x-ui.label>
                                <select id="model" name="model" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring">
                                    @foreach ([
                                        'claude-opus-4-7' => 'Claude Opus 4.7 (smartest)',
                                        'claude-sonnet-4-6' => 'Claude Sonnet 4.6 (balanced)',
                                        'claude-haiku-4-5-20251001' => 'Claude Haiku 4.5 (fastest)',
                                    ] as $m => $label)
                                        <option value="{{ $m }}" @selected(old('model', $settings->model) === $m)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="max_tokens">Max tokens</x-ui.label>
                                <x-ui.input id="max_tokens" name="max_tokens" type="number" min="64" max="8192" value="{{ old('max_tokens', $settings->max_tokens) }}" />
                            </div>
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="justify-between">
                        <x-ui.button type="submit">Save changes</x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>

            <form method="POST" action="{{ route('settings.ai.test') }}" class="mt-4">
                @csrf
                <x-ui.button type="submit" variant="outline" class="h-9">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Test AI connection
                </x-ui.button>
            </form>
        </x-settings-layout>
    </div>
</x-app-layout>
