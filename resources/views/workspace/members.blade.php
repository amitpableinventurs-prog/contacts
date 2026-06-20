<x-app-layout>
    <x-slot:header>Workspace / Members</x-slot:header>

    <div class="max-w-3xl space-y-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Members of {{ $team->name }}</h1>
                <p class="text-sm text-muted-foreground">Invite people, manage roles, remove members.</p>
            </div>
            @if (auth()->user()->isSuperAdmin())
            <a href="{{ route('workspace.export') }}"
               class="inline-flex h-9 items-center gap-1.5 rounded-md border border-input bg-background px-3 text-sm font-medium hover:bg-accent transition-colors whitespace-nowrap"
               title="Download a ZIP containing CSVs of every contact, message, email, reminder, group, and tag in this workspace.">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
                Export data
            </a>
            @endif
        </div>

        @if ($canManage)
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Invite someone</x-ui.card-title>
                    <x-ui.card-description>They'll receive an email with a link to accept.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <form method="POST" action="{{ route('workspace.invite') }}" class="flex flex-wrap gap-3 items-end">
                        @csrf
                        <div class="flex-1 min-w-[180px] space-y-1.5">
                            <x-ui.label for="email">Email</x-ui.label>
                            <x-ui.input id="email" type="email" name="email" required placeholder="teammate@example.com" />
                        </div>
                        <div class="space-y-1.5">
                            <x-ui.label for="role">Role</x-ui.label>
                            <select id="role" name="role" class="flex h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-sm focus-ring">
                                <option value="member">Member</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <x-ui.button type="submit">Send invite</x-ui.button>
                    </form>
                    @error('email') <p class="text-xs text-destructive mt-2">{{ $message }}</p> @enderror
                </x-ui.card-content>
            </x-ui.card>
        @endif

        @if ($invitations->isNotEmpty())
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Pending invitations</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="p-0">
                    <ul class="divide-y">
                        @foreach ($invitations as $inv)
                            <li class="flex items-center gap-3 p-4">
                                <div class="grid h-9 w-9 place-items-center rounded-full bg-muted text-xs text-muted-foreground">@&middot;</div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm">{{ $inv->email }}</div>
                                    <div class="text-xs text-muted-foreground">{{ ucfirst($inv->role) }} &middot; expires {{ $inv->expires_at?->diffForHumans() }}</div>
                                </div>
                                @if ($canManage)
                                    <form method="POST" action="{{ route('workspace.invitations.revoke', $inv) }}" onsubmit="return confirm('Revoke invitation?')">
                                        @csrf @method('DELETE')
                                        <x-ui.button type="submit" variant="ghost" size="sm" class="text-destructive hover:text-destructive">Revoke</x-ui.button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card-content>
            </x-ui.card>
        @endif

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Members</x-ui.card-title>
                <x-ui.card-description>{{ $members->count() + 1 }} {{ \Illuminate\Support\Str::plural('person', $members->count() + 1) }}</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="p-0">
                <ul class="divide-y">
                    {{-- Owner --}}
                    <li class="flex items-center gap-3 p-4">
                        <x-ui.avatar :name="$owner->name" :src="$owner->photo" size="sm" />
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm">{{ $owner->name }}</div>
                            <div class="text-xs text-muted-foreground">{{ $owner->email }}</div>
                        </div>
                        <x-ui.badge>Owner</x-ui.badge>
                    </li>
                    @foreach ($members as $member)
                        @if ($member->id !== $owner->id)
                            <li class="flex items-center gap-3 p-4">
                                <x-ui.avatar :name="$member->name" :src="$member->photo" size="sm" />
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm">{{ $member->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $member->email }}</div>
                                </div>
                                <x-ui.badge variant="outline">{{ ucfirst($member->pivot->role ?? 'member') }}</x-ui.badge>
                                @if ($canManage)
                                    <form method="POST" action="{{ route('workspace.members.remove', $member) }}" onsubmit="return confirm('Remove {{ addslashes($member->name) }} from this workspace?')">
                                        @csrf @method('DELETE')
                                        <x-ui.button type="submit" variant="ghost" size="sm" class="text-destructive hover:text-destructive">Remove</x-ui.button>
                                    </form>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-app-layout>
