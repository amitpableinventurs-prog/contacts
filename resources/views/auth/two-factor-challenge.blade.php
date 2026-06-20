<x-guest-layout>
    <div class="max-w-md w-full space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Two-factor authentication</h1>
            <p class="text-sm text-muted-foreground">Enter the 6-digit code from your authenticator app.</p>
        </div>

        <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4"
              x-data="{ mode: 'code' }">
            @csrf

            <div x-show="mode === 'code'" class="space-y-2">
                <x-ui.label for="code">Authenticator code</x-ui.label>
                <x-ui.input id="code" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6"
                            autocomplete="one-time-code" autofocus class="text-center text-2xl tracking-widest font-mono" />
            </div>

            <div x-show="mode === 'recovery'" x-cloak class="space-y-2">
                <x-ui.label for="recovery_code">Recovery code</x-ui.label>
                <x-ui.input id="recovery_code" name="recovery_code" autocomplete="off" class="font-mono" />
            </div>

            @error('code')<p class="text-xs text-destructive text-center">{{ $message }}</p>@enderror

            <x-ui.button type="submit" class="w-full h-11">Verify</x-ui.button>

            <button type="button" @click="mode = (mode === 'code' ? 'recovery' : 'code')"
                    class="block w-full text-center text-xs text-muted-foreground hover:text-foreground">
                <span x-show="mode === 'code'">Use a recovery code instead</span>
                <span x-show="mode === 'recovery'" x-cloak>Use the authenticator code instead</span>
            </button>
        </form>
    </div>
</x-guest-layout>
