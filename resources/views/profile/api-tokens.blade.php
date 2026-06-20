<x-app-layout>
    <x-slot:header>Profile / API tokens</x-slot:header>

    <div class="max-w-3xl mx-auto space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">API tokens</h1>
            <p class="text-sm text-muted-foreground">Personal access tokens for the JSON API at <code class="font-mono">/api/contacts</code>.</p>
        </div>

        @if (session('plainTextToken'))
            <x-ui.card class="border-success/40 bg-success/5">
                <x-ui.card-content class="p-4 space-y-2">
                    <div class="text-sm font-medium">Your new token</div>
                    <div class="text-xs text-muted-foreground">Copy it now &mdash; it won't be shown again.</div>
                    <code class="block break-all font-mono text-xs p-3 rounded-md border bg-card">{{ session('plainTextToken') }}</code>
                </x-ui.card-content>
            </x-ui.card>
        @endif

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Create a token</x-ui.card-title>
                <x-ui.card-description>Pick a memorable name (e.g. "Zapier", "Local dev").</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                <form method="POST" action="{{ route('api-tokens.store') }}" class="flex flex-wrap gap-3 items-end">
                    @csrf
                    <div class="flex-1 min-w-[180px] space-y-1.5">
                        <x-ui.label for="name">Name</x-ui.label>
                        <x-ui.input id="name" name="name" placeholder="My integration" required />
                    </div>
                    <x-ui.button type="submit">Create token</x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Existing tokens</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="p-0">
                @if ($tokens->isEmpty())
                    <p class="text-sm text-muted-foreground py-8 text-center">No tokens yet.</p>
                @else
                    <ul class="divide-y">
                        @foreach ($tokens as $token)
                            <li class="flex items-center gap-3 p-4">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm">{{ $token->name }}</div>
                                    <div class="text-xs text-muted-foreground">
                                        Created {{ $token->created_at->diffForHumans() }}
                                        @if ($token->last_used_at)
                                            &middot; last used {{ $token->last_used_at->diffForHumans() }}
                                        @else
                                            &middot; never used
                                        @endif
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('api-tokens.destroy', $token->id) }}" onsubmit="return confirm('Revoke this token?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="ghost" size="sm">Revoke</x-ui.button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-app-layout>
