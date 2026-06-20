<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight">Set a new password</h1>
            <p class="text-sm text-muted-foreground">Choose something memorable.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-1.5">
                <x-ui.label for="email">Email</x-ui.label>
                <x-ui.input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                @error('email') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <x-ui.label for="password">New password</x-ui.label>
                <x-ui.input id="password" type="password" name="password" required autocomplete="new-password" />
                @error('password') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <x-ui.label for="password_confirmation">Confirm new password</x-ui.label>
                <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <x-ui.button type="submit" class="w-full h-10">Reset password</x-ui.button>
        </form>
    </div>
</x-guest-layout>
