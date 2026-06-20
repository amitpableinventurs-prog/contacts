@auth
    <x-ui.dropdown-menu align="end">
        <x-slot:trigger>
            <button class="flex items-center gap-2 rounded-full focus-ring">
                <x-ui.avatar :name="auth()->user()->name" :src="auth()->user()->photo" size="sm" />
            </button>
        </x-slot:trigger>

        <div class="px-2 py-1.5">
            <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
            <div class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</div>
        </div>
        <x-ui.dropdown-menu-separator />
        <x-ui.dropdown-menu-item :href="route('profile.edit')">Profile</x-ui.dropdown-menu-item>
        @if (!auth()->user()->isManager())
            <x-ui.dropdown-menu-item :href="route('api-tokens.index')">API tokens</x-ui.dropdown-menu-item>
        @endif
        @if (auth()->user()->isSuperAdmin())
            <x-ui.dropdown-menu-item :href="route('settings.index')">Settings</x-ui.dropdown-menu-item>
        @endif
        <x-ui.dropdown-menu-separator />
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-ui.dropdown-menu-item type="submit" as="button">Log out</x-ui.dropdown-menu-item>
        </form>
    </x-ui.dropdown-menu>
@endauth
