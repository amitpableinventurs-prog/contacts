<x-app-layout>
    <x-slot:header>Settings / General</x-slot:header>

    <div class="max-w-5xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Settings</h1>
            <p class="text-sm text-muted-foreground">Configure your workspace.</p>
        </div>

        <x-settings-layout active="general">
            <form method="POST" action="{{ route('settings.general.update') }}">
                @csrf
                @method('PATCH')
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Workspace</x-ui.card-title>
                        <x-ui.card-description>Name, description, locale.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5 sm:col-span-2">
                            <x-ui.label for="app_name">Application name</x-ui.label>
                            <x-ui.input id="app_name" name="app_name" value="{{ old('app_name', $settings->app_name) }}" required />
                            @error('app_name') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5 sm:col-span-2">
                            <x-ui.label for="app_description">Tagline / description</x-ui.label>
                            <x-ui.textarea id="app_description" name="app_description" rows="2">{{ old('app_description', $settings->app_description) }}</x-ui.textarea>
                        </div>
                        <div class="space-y-1.5">
                            <x-ui.label for="default_locale">Default locale</x-ui.label>
                            <select id="default_locale" name="default_locale" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                                @foreach (['en' => 'English', 'fr' => 'Français', 'es' => 'Español', 'ar' => 'العربية', 'zh-CN' => '中文'] as $code => $label)
                                    <option value="{{ $code }}" @selected(old('default_locale', $settings->default_locale) === $code)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <x-ui.label for="footer_text">Footer text</x-ui.label>
                            <x-ui.input id="footer_text" name="footer_text" value="{{ old('footer_text', $settings->footer_text) }}" placeholder="Optional, shown on landing page" />
                        </div>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="mt-4">
                    <x-ui.card-header>
                        <x-ui.card-title>Access</x-ui.card-title>
                        <x-ui.card-description>Control who can join.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <label class="flex items-start gap-3 rounded-md border border-input p-3 cursor-pointer">
                            <input type="checkbox" name="allow_registration" value="1" @checked(old('allow_registration', $settings->allow_registration)) class="mt-0.5 rounded border-input" />
                            <div>
                                <div class="text-sm font-medium">Allow public registration</div>
                                <div class="text-xs text-muted-foreground">When off, /register returns 404 and new users join only via team invitation.</div>
                            </div>
                        </label>
                    </x-ui.card-content>
                    <x-ui.card-footer class="justify-end">
                        <x-ui.button type="submit">Save changes</x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-settings-layout>
    </div>
</x-app-layout>
