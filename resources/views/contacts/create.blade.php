<x-app-layout>
    <x-slot:header>Contacts / New</x-slot:header>

    <div class="space-y-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Add contact</h1>
            </div>

        <form action="{{ route('contacts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('contacts._form', ['contact' => null, 'groups' => $groups, 'tags' => $tags])
        </form>
    </div>
</x-app-layout>
