<x-app-layout>
    <x-slot:header>Contacts / {{ $contact->name }} / Edit</x-slot:header>

    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Edit {{ $contact->name }}</h1>
        </div>

        <form action="{{ route('contacts.update', $contact) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('contacts._form', ['contact' => $contact, 'groups' => $groups, 'tags' => $tags])
        </form>
    </div>
</x-app-layout>
