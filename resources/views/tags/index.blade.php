<x-app-layout>
    <x-slot:header>Library / Tags</x-slot:header>

    <div class="max-w-3xl space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Tags</h1>
            <p class="text-sm text-muted-foreground">Free-form labels you can apply to multiple contacts.</p>
        </div>

        @can('manage-tags')
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>New tag</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <form method="POST" action="{{ route('tags.store') }}" class="flex flex-wrap gap-3 items-end">
                    @csrf
                    <div class="flex-1 min-w-[200px] space-y-1.5">
                        <x-ui.label for="name">Name</x-ui.label>
                        <x-ui.input id="name" name="name" required placeholder="e.g. VIP" />
                    </div>
                    <div class="space-y-1.5">
                        <x-ui.label for="color">Accent</x-ui.label>
                        <select id="color" name="color" class="flex h-9 w-32 rounded-md border border-input bg-transparent px-2 text-sm shadow-sm focus-ring">
                            @foreach (['violet', 'blue', 'emerald', 'amber', 'rose', 'slate'] as $c)
                                <option value="{{ $c }}">{{ ucfirst($c) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-ui.button type="submit">Add tag</x-ui.button>
                </form>
                @error('name') <p class="text-xs text-destructive mt-2">{{ $message }}</p> @enderror
            </x-ui.card-content>
        </x-ui.card>
        @endcan

        <x-ui.card class="overflow-hidden">
            <x-ui.card-header>
                <x-ui.card-title>All tags</x-ui.card-title>
                <x-ui.card-description>{{ $tags->count() }} {{ \Illuminate\Support\Str::plural('tag', $tags->count()) }}</x-ui.card-description>
            </x-ui.card-header>
            @if ($tags->isEmpty())
                <x-ui.card-content>
                    <p class="text-sm text-muted-foreground text-center py-8">No tags yet.</p>
                </x-ui.card-content>
            @else
                <ul class="divide-y">
                    @foreach ($tags as $tag)
                        <li x-data="{ editing: false }" class="px-4 py-3">
                            <div x-show="!editing" class="flex items-center gap-3">
                                <x-ui.badge variant="outline">{{ $tag->name }}</x-ui.badge>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-muted-foreground">{{ $tag->contacts_count }} {{ \Illuminate\Support\Str::plural('contact', $tag->contacts_count) }}</div>
                                </div>
                                @can('manage-tags')
                                <x-ui.button type="button" variant="ghost" size="sm" @click="editing = true">Edit</x-ui.button>
                                <form method="POST" action="{{ route('tags.destroy', $tag) }}" onsubmit="return confirm('Delete tag {{ addslashes($tag->name) }}?')">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" variant="ghost" size="sm" class="text-destructive hover:text-destructive">Delete</x-ui.button>
                                </form>
                                @endcan
                            </div>
                            <form x-show="editing" x-cloak method="POST" action="{{ route('tags.update', $tag) }}" class="flex flex-wrap items-end gap-3">
                                @csrf @method('PATCH')
                                <x-ui.input name="name" value="{{ $tag->name }}" required class="flex-1 min-w-[200px]" />
                                <select name="color" class="flex h-9 w-32 rounded-md border border-input bg-transparent px-2 text-sm shadow-sm focus-ring">
                                    @foreach (['violet', 'blue', 'emerald', 'amber', 'rose', 'slate'] as $c)
                                        <option value="{{ $c }}" @selected($tag->color === $c)>{{ ucfirst($c) }}</option>
                                    @endforeach
                                </select>
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
