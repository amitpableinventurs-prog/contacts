<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl space-y-2">
                    <h2 class="text-lg font-medium">Two-factor authentication</h2>
                    <p class="text-sm text-muted-foreground">
                        @if (auth()->user()->two_factor_confirmed_at)
                            Enabled — your account requires a 6-digit code at every sign-in.
                        @else
                            Add an extra layer of security by requiring a code from your authenticator app.
                        @endif
                    </p>
                    <a href="{{ route('two-factor.show') }}"
                       class="inline-flex h-9 items-center rounded-md border border-input bg-background px-4 text-sm font-medium hover:bg-accent">
                        {{ auth()->user()->two_factor_confirmed_at ? 'Manage 2FA' : 'Set up 2FA' }} →
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
