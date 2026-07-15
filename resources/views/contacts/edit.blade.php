<x-app-layout>
    <x-slot:header>Contacts / {{ $contact->name }} / Edit</x-slot:header>

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-2xl font-semibold tracking-tight">Edit {{ $contact->name }}</h1>
                @if ($contact->status === 'banned')
                    <span class="inline-flex items-center rounded-md bg-red-700 px-2 py-0.5 text-xs font-semibold text-white">Banned</span>
                @elseif ($contact->status === 'suspended')
                    <span class="inline-flex items-center rounded-md bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-800">Suspended</span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('contacts.show', $contact) }}">
                    <x-ui.button variant="outline" size="sm">View contact</x-ui.button>
                </a>
                <x-ui.dropdown-menu align="end">
                    <x-slot:trigger>
                        <button class="rounded-md border border-input bg-background h-9 px-3 hover:bg-accent">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                        </button>
                    </x-slot:trigger>
                    @if ($contact->status !== 'suspended')
                        <form method="POST" action="{{ route('contacts.suspend', $contact) }}">
                            @csrf
                            <x-ui.dropdown-menu-item as="button" type="submit" class="text-orange-600">Suspend</x-ui.dropdown-menu-item>
                        </form>
                    @endif
                    @if ($contact->status !== 'banned')
                        <form method="POST" action="{{ route('contacts.ban', $contact) }}">
                            @csrf
                            <x-ui.dropdown-menu-item as="button" type="submit" class="text-red-700">Ban</x-ui.dropdown-menu-item>
                        </form>
                    @endif
                    @if ($contact->status !== 'active')
                        <form method="POST" action="{{ route('contacts.reactivate', $contact) }}">
                            @csrf
                            <x-ui.dropdown-menu-item as="button" type="submit">Reactivate</x-ui.dropdown-menu-item>
                        </form>
                    @endif
                </x-ui.dropdown-menu>
            </div>
        </div>

        <form action="{{ route('contacts.update', $contact) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('contacts._form', ['contact' => $contact, 'groups' => $groups, 'tags' => $tags])
        </form>
    </div>
</x-app-layout>
