<x-guest-layout>
    <div class="space-y-4 text-center">
        <h1 class="text-2xl font-semibold tracking-tight">Invitation expired</h1>
        <p class="text-sm text-muted-foreground">This invitation link is no longer valid. Ask the workspace owner to send a new one.</p>
        <a href="{{ route('login') }}">
            <x-ui.button variant="outline">Back to sign in</x-ui.button>
        </a>
    </div>
</x-guest-layout>
