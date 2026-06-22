<x-app-layout>
    <x-slot:header>Settings / Mail</x-slot:header>

    <div class="max-w-5xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Settings</h1>
            <p class="text-sm text-muted-foreground">How outbound email is delivered.</p>
        </div>

        <x-settings-layout active="mail">
            <form method="POST" action="{{ route('settings.mail.update') }}" x-data="{ mailer: '{{ old('mailer', $settings->mailer) }}' }">
                @csrf
                @method('PATCH')

                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Sender</x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <x-ui.label for="from_email">From email</x-ui.label>
                            <x-ui.input id="from_email" name="from_email" type="email" value="{{ old('from_email', $settings->from_email) }}" required />
                            @error('from_email') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <x-ui.label for="from_name">From name</x-ui.label>
                            <x-ui.input id="from_name" name="from_name" value="{{ old('from_name', $settings->from_name) }}" required />
                        </div>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="mt-4">
                    <x-ui.card-header>
                        <x-ui.card-title>Driver</x-ui.card-title>
                        <x-ui.card-description>Switch how email is delivered.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">
                        <div class="space-y-1.5">
                            <x-ui.label for="mailer">Mailer</x-ui.label>
                            <select id="mailer" name="mailer" x-model="mailer" class="flex h-9 w-full sm:w-64 rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring">
                                <option value="log">Log (writes to laravel.log; dev-only)</option>
                                <option value="smtp">SMTP</option>
                                <option value="resend">Resend</option>
                                <option value="mailgun">Mailgun</option>
                                <option value="postmark">Postmark</option>
                                <option value="ses">Amazon SES</option>
                            </select>
                        </div>

                        {{-- SMTP fields --}}
                        <div x-show="mailer === 'smtp'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-md border border-input p-4 bg-muted/20">
                            <div class="space-y-1.5">
                                <x-ui.label for="smtp_host">Host</x-ui.label>
                                <x-ui.input id="smtp_host" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host) }}" placeholder="smtp.mailgun.org" />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="smtp_port">Port</x-ui.label>
                                <x-ui.input id="smtp_port" name="smtp_port" type="number" value="{{ old('smtp_port', $settings->smtp_port) }}" placeholder="587" />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="smtp_username">Username</x-ui.label>
                                <x-ui.input id="smtp_username" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username) }}" />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="smtp_password">Password</x-ui.label>
                                <x-ui.input id="smtp_password" name="smtp_password" type="password" placeholder="{{ $settings->smtp_password ? '••••• (set)' : 'leave blank to keep' }}" />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="smtp_encryption">Encryption</x-ui.label>
                                <select id="smtp_encryption" name="smtp_encryption" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring">
                                    @foreach (['tls', 'ssl', 'starttls'] as $enc)
                                        <option value="{{ $enc }}" @selected(old('smtp_encryption', $settings->smtp_encryption) === $enc)>{{ strtoupper($enc) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- API driver fields --}}
                        <div x-show="['resend','mailgun','postmark','ses'].includes(mailer)" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-md border border-input p-4 bg-muted/20">
                            <div class="space-y-1.5">
                                <x-ui.label for="api_key">
                                    API key
                                    <span x-show="mailer === 'ses'" class="text-muted-foreground font-normal">(ACCESS_KEY|SECRET|REGION)</span>
                                </x-ui.label>
                                <x-ui.input id="api_key" name="api_key" type="password" placeholder="{{ $settings->api_key ? '••••• (set)' : 'paste provider API key' }}" />
                            </div>
                            <div class="space-y-1.5" x-show="mailer !== 'ses'">
                                <x-ui.label for="api_domain">Domain (mailgun)</x-ui.label>
                                <x-ui.input id="api_domain" name="api_domain" value="{{ old('api_domain', $settings->api_domain) }}" placeholder="mg.example.com" />
                            </div>
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="justify-between">
                        <div class="flex items-center gap-2">
                            <x-ui.input id="test_to_email"
                                        type="email"
                                        placeholder="Test recipient email…"
                                        class="w-56 h-9 text-sm"
                                        value="{{ Auth::user()->email }}" />
                            <button type="button"
                                    onclick="
                                        document.getElementById('test_to_hidden').value = document.getElementById('test_to_email').value;
                                        document.getElementById('mail-test-form').submit();
                                    "
                                    class="inline-flex h-9 items-center rounded-md border border-input px-4 text-sm font-medium hover:bg-accent">
                                Send test email
                            </button>
                        </div>
                        <x-ui.button type="submit">Save changes</x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>

            {{-- Separate form for test send so it doesn't carry the unsaved fields --}}
            <form method="POST" action="{{ route('settings.mail.test') }}" class="hidden" id="mail-test-form">
                @csrf
                <input type="hidden" name="test_to" id="test_to_hidden" value="{{ Auth::user()->email }}" />
            </form>
        </x-settings-layout>
    </div>
</x-app-layout>
