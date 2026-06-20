<x-app-layout>
    <x-slot:header>Users / {{ $user->name }}</x-slot:header>

    <div class="max-w-2xl space-y-4">
        <div class="flex items-center gap-3">
            <x-ui.avatar :name="$user->name" size="lg" />
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ $user->name }}</h1>
                <p class="text-sm text-muted-foreground">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Profile --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Profile</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
                    @csrf @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <x-ui.label for="name">Full name</x-ui.label>
                            <x-ui.input id="name" name="name" value="{{ old('name', $user->name) }}" required />
                            @error('name') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <x-ui.label for="email">Email</x-ui.label>
                            <x-ui.input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                            @error('email') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <x-ui.label for="role">Role</x-ui.label>
                            <select id="role" name="role" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-ring">
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>
                                        {{ ucwords(str_replace('_', ' ', $role)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <x-ui.label>Status</x-ui.label>
                            <label class="flex items-center gap-2 text-sm cursor-pointer h-9">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-input"
                                       @checked(old('is_active', $user->is_active ?? true)) />
                                Active (can log in)
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-ui.button type="submit">Save changes</x-ui.button>
                    </div>
                </form>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Change password --}}
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Change password</x-ui.card-title>
                <x-ui.card-description>Set a new password for this user.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                <form method="POST" action="{{ route('users.password', $user) }}" class="space-y-4">
                    @csrf @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <x-ui.label for="password">New password</x-ui.label>
                            <x-ui.input id="password" type="password" name="password" required />
                            @error('password') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <x-ui.label for="password_confirmation">Confirm</x-ui.label>
                            <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-ui.button type="submit" variant="outline">Change password</x-ui.button>
                    </div>
                </form>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Danger zone --}}
        @if ($user->id !== auth()->id())
            <x-ui.card class="border-destructive/30">
                <x-ui.card-header>
                    <x-ui.card-title class="text-destructive">Danger zone</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium">Delete this user</p>
                            <p class="text-xs text-muted-foreground">This action cannot be undone.</p>
                        </div>
                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                            @csrf @method('DELETE')
                            <x-ui.button type="submit" variant="destructive" size="sm">Delete user</x-ui.button>
                        </form>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        @endif
    </div>
</x-app-layout>
