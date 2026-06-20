<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2 text-center">
            @php $brand = app(\App\Settings\GeneralSettings::class); @endphp
            @if ($brand->logo_path)
                <img src="{{ asset('storage/'.$brand->logo_path) }}" alt="{{ $brand->app_name }}" class="h-12 w-12 rounded-xl object-contain mx-auto" />
            @else
                <div class="mx-auto grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground text-xl font-bold">
                    {{ mb_substr($brand->app_name, 0, 1) }}
                </div>
            @endif
            <h1 class="text-2xl font-semibold tracking-tight">{{ $brand->app_name }}</h1>
            <p class="text-sm text-muted-foreground">Sign in to continue</p>
        </div>

        @if (session('status'))
            <div class="rounded-md border border-success/30 bg-success/5 px-3 py-2 text-sm text-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <x-ui.label for="email">Email</x-ui.label>
                <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com" />
                @error('email') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <x-ui.label for="password">Password</x-ui.label>
                    <a href="{{ route('password.request') }}" class="text-xs text-muted-foreground hover:text-foreground">Forgot password?</a>
                </div>
                <x-ui.input id="password" type="password" name="password" required autocomplete="current-password" />
                @error('password') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-muted-foreground cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-input" />
                Keep me signed in
            </label>

            <x-ui.button type="submit" class="w-full h-10">Sign in</x-ui.button>
        </form>
    </div>
</x-guest-layout>
