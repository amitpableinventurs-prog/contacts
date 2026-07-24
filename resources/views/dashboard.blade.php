<x-app-layout>
    <x-slot:header>Dashboard</x-slot:header>

    @php
        $isClerk = auth()->user()->isClerk();
        $isManager = auth()->user()->isManager();
    @endphp

    <div class="flex items-start justify-center min-h-[60vh] pt-16">
        <div class="w-full max-w-lg space-y-6">
            <div class="text-center space-y-1">
                <h1 class="text-2xl font-bold tracking-tight">
                    @if ($isClerk)
                        Find a Contact
                    @else
                        Search Contacts
                    @endif
                </h1>
                <p class="text-sm text-muted-foreground">
                    @if ($isClerk)
                        Search by phone number to find and manage contact interactions.
                    @else
                        Search by phone number or name to find and manage your contacts.
                    @endif
                </p>
            </div>

            <form method="GET" action="{{ route('contacts.index') }}" class="flex gap-2">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="{{ $isClerk ? 'number' : 'q' }}"
                        autofocus
                        placeholder="{{ $isClerk ? '91 98765 43210 or +1 555-123-4567' : 'Search by name, phone, email, company…' }}"
                        class="flex h-11 w-full rounded-md border border-input bg-background pl-9 pr-4 text-sm shadow-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                    />
                </div>
                <x-ui.button type="submit" class="h-11 px-6">Search</x-ui.button>
            </form>

            @unless ($isClerk || $isManager)
                <div class="text-center">
                    <a href="{{ route('contacts.index') }}" class="text-sm text-muted-foreground hover:text-foreground underline underline-offset-4">View all contacts →</a>
                </div>
            @endunless

            @if ($isClerk && !empty($recentSearches))
                <div class="space-y-2">
                    <h2 class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Recent searches</h2>
                    <div class="divide-y rounded-md border">
                        @foreach ($recentSearches as $search)
                            <a href="{{ $search['contact_id'] ? route('contacts.show', $search['contact_id']) : route('contacts.index', ['number' => $search['number']]) }}"
                               class="flex items-center justify-between gap-2 px-3 py-2.5 text-sm hover:bg-accent transition-colors">
                                <span class="min-w-0 truncate">
                                    @if ($search['name'])
                                        <span class="font-medium">{{ $search['name'] }}</span>
                                        <span class="text-muted-foreground"> · {{ $search['number'] }}</span>
                                    @else
                                        <span class="text-muted-foreground">No match for {{ $search['number'] }}</span>
                                    @endif
                                </span>
                                <svg class="h-4 w-4 shrink-0 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
