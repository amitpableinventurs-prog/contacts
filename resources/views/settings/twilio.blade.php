<x-app-layout>
    <x-slot:header>Settings / Twilio</x-slot:header>

    <div class="max-w-5xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Settings</h1>
            <p class="text-sm text-muted-foreground">Configure your workspace.</p>
        </div>

        <x-settings-layout active="twilio">
            <form method="POST" action="{{ route('settings.twilio.update') }}">
                @csrf
                @method('PATCH')
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Twilio credentials</x-ui.card-title>
                        <x-ui.card-description>Used for outbound SMS and (optionally) browser voice calling.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-4">

                        <label class="flex items-center gap-3 rounded-md border border-input p-3">
                            <input type="checkbox" name="fake_mode" value="1" @checked(old('fake_mode', $settings->fake_mode)) class="rounded border-input" />
                            <div class="text-sm">
                                <div class="font-medium">Fake mode</div>
                                <div class="text-muted-foreground text-xs">Outbound SMS and calls are simulated &mdash; nothing is sent. Turn off once real credentials are entered.</div>
                            </div>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <x-ui.label for="sid">Account SID</x-ui.label>
                                <x-ui.input id="sid" name="sid" value="{{ old('sid', $settings->sid) }}" placeholder="AC..." />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="token">Auth token</x-ui.label>
                                <x-ui.input id="token" name="token" type="password" placeholder="{{ $settings->token ? '••••••• (set)' : 'leave blank to keep current' }}" />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="phone_number">Twilio phone number</x-ui.label>
                                <x-ui.input id="phone_number" name="phone_number" value="{{ old('phone_number', $settings->phone_number) }}" placeholder="+1 415 555 0142" />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="application_sid">Application SID (Voice)</x-ui.label>
                                <x-ui.input id="application_sid" name="application_sid" value="{{ old('application_sid', $settings->application_sid) }}" placeholder="AP..." />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="api_key">API key SID</x-ui.label>
                                <x-ui.input id="api_key" name="api_key" value="{{ old('api_key', $settings->api_key) }}" placeholder="SK..." />
                            </div>
                            <div class="space-y-1.5">
                                <x-ui.label for="api_secret">API secret</x-ui.label>
                                <x-ui.input id="api_secret" name="api_secret" type="password" placeholder="{{ $settings->api_secret ? '••••••• (set)' : 'leave blank to keep current' }}" />
                            </div>
                        </div>

                        <div class="rounded-md border border-dashed p-3 text-xs text-muted-foreground">
                            <strong class="text-foreground">Inbound webhook URL:</strong>
                            <code class="font-mono">{{ route('webhooks.twilio.sms') }}</code>
                            <br>
                            Set this as the "A MESSAGE COMES IN" webhook on your Twilio phone number to receive replies.
                        </div>
                    </x-ui.card-content>
                    <x-ui.card-footer class="justify-end">
                        <x-ui.button type="submit">Save changes</x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-settings-layout>
    </div>
</x-app-layout>
