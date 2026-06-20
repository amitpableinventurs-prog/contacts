<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight">Create your workspace</h1>
            <p class="text-sm text-muted-foreground">A personal workspace will be set up for you automatically.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <x-ui.label for="name">Name</x-ui.label>
                <x-ui.input id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Jane Doe" />
                @error('name') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <x-ui.label for="email">Email</x-ui.label>
                <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com" />
                @error('email') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <x-ui.label for="password">Password</x-ui.label>
                <x-ui.input id="password" type="password" name="password" required autocomplete="new-password" />
                @error('password') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <x-ui.label for="password_confirmation">Confirm password</x-ui.label>
                <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <x-ui.button type="submit" class="w-full h-10">Create account</x-ui.button>
        </form>

        <p class="text-center text-sm text-muted-foreground">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-foreground hover:underline">Sign in</a>
        </p>
    </div>
</x-guest-layout>
