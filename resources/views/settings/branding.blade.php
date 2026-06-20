<x-app-layout>
    <x-slot:header>Settings / Branding</x-slot:header>

    <div class="max-w-5xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Settings</h1>
            <p class="text-sm text-muted-foreground">Make it yours — logo, colors, email tone.</p>
        </div>

        <x-settings-layout active="branding">
            <form method="POST" action="{{ route('settings.branding.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Logo</x-ui.card-title>
                        <x-ui.card-description>PNG, JPG, SVG, or WebP up to 2MB. Shown in the sidebar and landing nav.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-3">
                        @if ($settings->logo_path)
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Current logo" class="h-14 w-14 rounded-md border bg-card object-contain p-1" />
                                <label class="inline-flex items-center gap-2 text-sm text-muted-foreground cursor-pointer">
                                    <input type="checkbox" name="remove_logo" value="1" class="rounded border-input" />
                                    Remove current logo
                                </label>
                            </div>
                        @endif
                        <x-ui.input type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp" />
                        @error('logo') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="mt-4">
                    <x-ui.card-header>
                        <x-ui.card-title>Primary color</x-ui.card-title>
                        <x-ui.card-description>Drives buttons, links, and accents across the whole app.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="flex items-center gap-3" x-data="{ c: '{{ old('primary_color', $settings->primary_color) }}' }">
                            <input id="primary_color" name="primary_color" type="color" x-model="c" class="h-12 w-16 rounded-md border border-input cursor-pointer" />
                            <x-ui.input x-model="c" readonly class="w-40 font-mono" />
                            <div class="flex-1"></div>
                            <button type="button" class="inline-flex h-9 items-center justify-center rounded-md text-primary-foreground px-4 text-sm font-medium shadow" :style="'background-color:' + c">Preview</button>
                        </div>
                        @error('primary_color') <p class="text-xs text-destructive mt-2">{{ $message }}</p> @enderror
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="mt-4">
                    <x-ui.card-header>
                        <x-ui.card-title>Email signature</x-ui.card-title>
                        <x-ui.card-description>Appended to outbound contact emails. Plain text.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <x-ui.textarea name="email_signature" rows="4" placeholder="— Jane Doe&#10;Head of Growth · {{ $settings->app_name }}">{{ old('email_signature', $settings->email_signature) }}</x-ui.textarea>
                    </x-ui.card-content>
                    <x-ui.card-footer class="justify-end">
                        <x-ui.button type="submit">Save changes</x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </form>
        </x-settings-layout>
    </div>
</x-app-layout>
