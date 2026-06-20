<x-app-layout :title="'Two-factor authentication'">
    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Two-factor authentication</h1>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Require a 6-digit code from your authenticator app at every sign-in.
                </p>
            </div>
            <a href="{{ route('profile.edit') }}" class="text-sm text-muted-foreground hover:text-foreground">← Back to profile</a>
        </div>

        @if (! $user->two_factor_secret)
            {{-- Not yet enabled --}}
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Enable 2FA</x-ui.card-title>
                    <x-ui.card-description>You'll need an authenticator app like Google Authenticator, 1Password, or Authy.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <x-ui.button type="submit">Start setup</x-ui.button>
                    </form>
                </x-ui.card-content>
            </x-ui.card>

        @elseif (! $user->two_factor_confirmed_at)
            {{-- Setup in progress — show QR + recovery codes + confirm form --}}
            <x-ui.card class="bg-warning/5 border-warning/30">
                <x-ui.card-header>
                    <x-ui.card-title>Finish setup</x-ui.card-title>
                    <x-ui.card-description>Scan the QR code with your authenticator, then enter a 6-digit code to finish.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-5">
                    <div class="flex items-start gap-5 flex-col sm:flex-row">
                        <div class="bg-white p-2 rounded-lg shadow-sm">{!! $qr !!}</div>
                        <div class="text-sm space-y-2 flex-1">
                            <p class="text-muted-foreground">Or enter this code manually in your authenticator:</p>
                            <code class="block font-mono text-xs bg-muted p-2 rounded select-all break-all">{{ \App\Support\TwoFactor::secret($user) }}</code>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-3">
                        @csrf
                        <x-ui.label for="code">6-digit code</x-ui.label>
                        <x-ui.input id="code" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required autofocus />
                        @error('code')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                        <x-ui.button type="submit">Confirm</x-ui.button>
                    </form>

                    {{-- Sibling form (HTML doesn't allow form nesting). --}}
                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="outline">Cancel setup</x-ui.button>
                    </form>
                </x-ui.card-content>
            </x-ui.card>

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Recovery codes</x-ui.card-title>
                    <x-ui.card-description>Save these somewhere safe. Each code can be used once if you lose access to your authenticator.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                        @foreach ($codes as $c)
                            <code class="bg-muted px-2 py-1 rounded select-all">{{ $c }}</code>
                        @endforeach
                    </div>
                </x-ui.card-content>
            </x-ui.card>

        @else
            {{-- Already enabled --}}
            <x-ui.card class="bg-success/5 border-success/30">
                <x-ui.card-content class="p-4 flex items-center gap-3">
                    <div class="grid h-10 w-10 place-items-center rounded-full bg-success text-success-foreground shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1">
                        <strong class="block">2FA is enabled</strong>
                        <span class="text-sm text-muted-foreground">Confirmed on {{ $user->two_factor_confirmed_at->format('M j, Y g:i a') }}.</span>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Recovery codes</x-ui.card-title>
                    <x-ui.card-description>{{ count(\App\Support\TwoFactor::recoveryCodes($user)) }} unused codes remaining.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                        @foreach (\App\Support\TwoFactor::recoveryCodes($user) as $c)
                            <code class="bg-muted px-2 py-1 rounded select-all">{{ $c }}</code>
                        @endforeach
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <x-ui.card class="border-destructive/30">
                <x-ui.card-header>
                    <x-ui.card-title>Disable 2FA</x-ui.card-title>
                    <x-ui.card-description>This will remove the secret and recovery codes. Your next sign-in won't require a code.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <form method="POST" action="{{ route('two-factor.disable') }}" onsubmit="return confirm('Disable 2FA? You can re-enable it anytime.')">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="destructive">Disable 2FA</x-ui.button>
                    </form>
                </x-ui.card-content>
            </x-ui.card>
        @endif
    </div>
</x-app-layout>
