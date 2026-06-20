<x-app-layout>
    <x-slot:header>Library / Groups</x-slot:header>

    <div class="max-w-3xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Groups</h1>
            <p class="text-sm text-muted-foreground">Organize contacts into segments. Each contact can belong to one group.</p>
        </div>

        @if (!auth()->user()->isManager())
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>New group</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <form method="POST" action="{{ route('groups.store') }}" class="flex flex-wrap gap-3 items-end">
                    @csrf
                    <div class="flex-1 min-w-[200px] space-y-1.5">
                        <x-ui.label for="name">Name</x-ui.label>
                        <x-ui.input id="name" name="name" required placeholder="e.g. Customers" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui.label for="color">Color</x-ui.label>
                        <input id="color" name="color" type="color" value="#a855f7" class="h-9 w-12 rounded-md border border-input cursor-pointer" />
                    </div>
                    <x-ui.button type="submit">Add group</x-ui.button>
                </form>
                @error('name') <p class="text-xs text-destructive mt-2">{{ $message }}</p> @enderror
            </x-ui.card-content>
        </x-ui.card>
        @endif

        <x-ui.card class="overflow-hidden">
            <x-ui.card-header>
                <x-ui.card-title>All groups</x-ui.card-title>
                <x-ui.card-description>{{ $groups->count() }} {{ \Illuminate\Support\Str::plural('group', $groups->count()) }}</x-ui.card-description>
            </x-ui.card-header>
            @if ($groups->isEmpty())
                <x-ui.card-content>
                    <p class="text-sm text-muted-foreground text-center py-8">No groups yet.</p>
                </x-ui.card-content>
            @else
                <ul class="divide-y">
                    @foreach ($groups as $group)
                        <li x-data="{ editing: false }" class="px-4 py-3">
                            <div x-show="!editing" class="flex items-center gap-3">
                                <span class="h-3 w-3 rounded-full shrink-0" style="background:{{ $group->color ?: '#a855f7' }}"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium">{{ $group->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $group->contacts_count }} {{ \Illuminate\Support\Str::plural('contact', $group->contacts_count) }}</div>
                                </div>
                                <x-ui.button type="button" variant="ghost" size="sm" @click="editing = true">Edit</x-ui.button>
                                <form method="POST" action="{{ route('groups.destroy', $group) }}" onsubmit="return confirm('Delete group {{ addslashes($group->name) }}? Contacts will be moved to no-group.')">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" variant="ghost" size="sm" class="text-destructive hover:text-destructive">Delete</x-ui.button>
                                </form>
                            </div>
                            <form x-show="editing" x-cloak method="POST" action="{{ route('groups.update', $group) }}" class="flex flex-wrap items-end gap-3">
                                @csrf @method('PATCH')
                                <x-ui.input name="name" value="{{ $group->name }}" required class="flex-1 min-w-[200px]" />
                                <input name="color" type="color" value="{{ $group->color ?: '#a855f7' }}" class="h-9 w-12 rounded-md border border-input cursor-pointer" />
                                <x-ui.button type="submit" size="sm">Save</x-ui.button>
                                <x-ui.button type="button" variant="ghost" size="sm" @click="editing = false">Cancel</x-ui.button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
