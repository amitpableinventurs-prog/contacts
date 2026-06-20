<x-installer-layout :step="$step">
    <div class="text-center space-y-3 mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Create your admin account</h1>
        <p class="text-base text-muted-foreground">The first account you create owns the workspace. A personal team is set up automatically.</p>
    </div>

    @if ($errors->any())
        <x-ui.card class="bg-destructive/5 border-destructive/30 mb-6">
            <x-ui.card-content class="p-4 flex items-start gap-3 text-sm">
                <svg class="h-5 w-5 mt-0.5 text-destructive shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/></svg>
                <div class="text-muted-foreground">
                    @foreach ($errors->all() as $error)
                        <span class="block">{{ $error }}</span>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>
    @endif

    <form method="POST" action="{{ route('install.admin.save') }}">
        @csrf

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Workspace branding</x-ui.card-title>
                <x-ui.card-description>You can change this anytime from Settings → Branding.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="space-y-1.5">
                    <x-ui.label for="app_name">Application name</x-ui.label>
                    <x-ui.input id="app_name" name="app_name" value="{{ old('app_name', 'Contacts') }}" required />
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="mt-4">
            <x-ui.card-header>
                <x-ui.card-title>Your account</x-ui.card-title>
                <x-ui.card-description>This is the account you'll sign in with.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                <div class="space-y-1.5">
                    <x-ui.label for="name">Your name</x-ui.label>
                    <x-ui.input id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Jane Doe" />
                </div>

                <div class="space-y-1.5">
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <x-ui.label for="password">Password</x-ui.label>
                        <x-ui.input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" />
                        <p class="text-xs text-muted-foreground">At least 8 characters.</p>
                    </div>
                    <div class="space-y-1.5">
                        <x-ui.label for="password_confirmation">Confirm</x-ui.label>
                        <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" />
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="mt-4">
            <x-ui.card-content class="p-5">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="seed_demo" value="1" {{ old('seed_demo', '1') ? 'checked' : '' }}
                           class="mt-1 h-4 w-4 rounded border-input text-primary focus:ring-primary" />
                    <div class="flex-1">
                        <div class="font-medium text-sm">Load demo data so I can see the app in action</div>
                        <p class="text-xs text-muted-foreground mt-0.5">
                            Adds 12 sample contacts (Ada Lovelace, Grace Hopper, …), 4 groups, 7 tags, and 5 follow-up reminders. Safe to delete from inside the app anytime.
                        </p>
                    </div>
                </label>
            </x-ui.card-content>
        </x-ui.card>

        <div class="flex items-center justify-between pt-8">
            <a href="{{ route('install.database') }}"
               class="inline-flex h-9 items-center rounded-md border border-input bg-background px-4 text-sm font-medium hover:bg-accent transition-colors">
                ← Back
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-md text-base font-medium bg-gradient-to-br from-primary to-fuchsia-500 text-white shadow-lg shadow-primary/30 hover:shadow-xl transition-all">
                Create account
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</x-installer-layout>
