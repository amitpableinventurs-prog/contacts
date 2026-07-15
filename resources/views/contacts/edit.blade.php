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
                @can('banOrReactivate', $contact)
                    @if ($contact->status !== 'active')
                        <form method="POST" action="{{ route('contacts.reactivate', $contact) }}" class="inline">
                            @csrf
                            <x-ui.button type="submit" variant="outline" size="sm" class="text-green-600 border-green-600 hover:bg-green-50">Reactivate</x-ui.button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('contacts.ban', $contact) }}" class="inline">
                            @csrf
                            <x-ui.button type="submit" variant="outline" size="sm" class="text-red-900 border-red-900 hover:bg-red-50">Ban</x-ui.button>
                        </form>
                    @endif
                @endcan
                @can('manage', $contact)
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
                    </x-ui.dropdown-menu>
                @endcan
            </div>
        </div>

        @php
            $canEdit = Auth::user()->can('update', $contact);
        @endphp

        @if (!$canEdit)
            <div class="rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                <strong>View-only mode:</strong> Clerks can only add notes. To edit contact fields, you need manager or higher privileges.
            </div>
        @endif

        <form action="{{ route('contacts.update', $contact) }}" method="POST" enctype="multipart/form-data" @if (!$canEdit) onsubmit="return false;" @endif>
            @csrf
            @method('PUT')
            @include('contacts._form', ['contact' => $contact, 'groups' => $groups, 'tags' => $tags, 'canEdit' => $canEdit])
        </form>
    </div>
</x-app-layout>
