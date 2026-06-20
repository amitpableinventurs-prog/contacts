<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight">You're invited</h1>
            <p class="text-sm text-muted-foreground">
                <strong>{{ $invitation->invitedBy->name }}</strong> invited you to join <strong>{{ $invitation->team->name }}</strong> as a {{ $invitation->role }}.
            </p>
        </div>

        @auth
            @if (auth()->user()->email === $invitation->email)
                <form method="POST" action="{{ route('invitations.accept.post', $invitation->token) }}" class="space-y-3">
                    @csrf
                    <x-ui.button type="submit" class="w-full h-10">Accept invitation</x-ui.button>
                </form>
            @else
                <div class="rounded-md border border-warning/30 bg-warning/5 p-3 text-sm">
                    This invitation is for <strong>{{ $invitation->email }}</strong>, but you're signed in as <strong>{{ auth()->user()->email }}</strong>.
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-ui.button type="submit" variant="outline" class="w-full">Sign out &amp; switch account</x-ui.button>
                </form>
            @endif
        @else
            <div class="space-y-3">
                <p class="text-sm">Sign in or create an account with <strong>{{ $invitation->email }}</strong> to accept.</p>
                <a href="{{ route('login') }}" class="block">
                    <x-ui.button class="w-full h-10">Sign in</x-ui.button>
                </a>
                <a href="{{ route('register') }}" class="block">
                    <x-ui.button variant="outline" class="w-full h-10">Create account</x-ui.button>
                </a>
            </div>
        @endauth
    </div>
</x-guest-layout>
