<x-app-layout>
    <x-slot:header>Contacts / Pending Approvals</x-slot:header>

    <div class="space-y-8">
        {{-- New contacts submitted by Clerks --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">New contacts</h1>
                    <p class="text-sm text-muted-foreground">
                        Contacts submitted by Clerks that require your approval before becoming active.
                    </p>
                </div>
                <x-ui.badge class="text-sm px-3 py-1">{{ $contacts->total() }} pending</x-ui.badge>
            </div>

            @if ($contacts->isEmpty())
                <x-ui.card>
                    <x-ui.card-content class="py-16 text-center">
                        <div class="mx-auto h-12 w-12 rounded-full bg-green-100 grid place-items-center mb-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-base">All caught up!</h3>
                        <p class="text-sm text-muted-foreground mt-1">No contacts waiting for approval.</p>
                    </x-ui.card-content>
                </x-ui.card>
            @else
                <div class="space-y-3">
                    @foreach ($contacts as $contact)
                        <x-ui.card>
                            <x-ui.card-content class="p-4">
                                <div class="flex flex-wrap items-start gap-4">

                                    {{-- Contact info --}}
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <x-ui.avatar :name="$contact->name" :src="$contact->photo ? asset('storage/'.$contact->photo) : null" size="md" />
                                        <div class="min-w-0">
                                            <div class="font-semibold text-base">{{ $contact->name }}</div>
                                            <div class="text-sm text-muted-foreground">
                                                {{ collect([$contact->email, $contact->phone])->filter()->join(' · ') ?: '—' }}
                                            </div>
                                            @if ($contact->company)
                                                <div class="text-xs text-muted-foreground">{{ $contact->company }}@if($contact->job_title) · {{ $contact->job_title }}@endif</div>
                                            @endif
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @if ($contact->group)
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-input px-1.5 py-0.5 text-xs">
                                                        <span class="h-1.5 w-1.5 rounded-full" style="background:{{ $contact->group->color ?: '#a855f7' }}"></span>
                                                        {{ $contact->group->name }}
                                                    </span>
                                                @endif
                                                @foreach ($contact->tags->take(3) as $tag)
                                                    <x-ui.badge variant="outline" class="text-xs">{{ $tag->name }}</x-ui.badge>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Meta --}}
                                    <div class="text-xs text-muted-foreground shrink-0 text-right space-y-0.5">
                                        <div>Submitted by <span class="font-medium text-foreground">{{ $contact->owner?->name ?? '—' }}</span></div>
                                        <div>{{ $contact->created_at->diffForHumans() }}</div>
                                        @if ($contact->notes)
                                            <div class="mt-1 text-xs italic max-w-xs text-right">{{ \Illuminate\Support\Str::limit($contact->notes, 80) }}</div>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="{{ route('contacts.show', $contact) }}" class="inline-flex h-8 items-center gap-1 rounded-md border border-input bg-background px-3 text-xs font-medium hover:bg-accent transition-colors">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('contacts.approve', $contact) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-green-600 px-3 text-xs font-medium text-white hover:bg-green-700 transition-colors">
                                                ✓ Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('contacts.reject', $contact) }}" onsubmit="return confirm('Reject {{ addslashes($contact->name) }}?')">
                                            @csrf
                                            <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-red-600 px-3 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                                ✕ Reject
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </x-ui.card-content>
                        </x-ui.card>
                    @endforeach
                </div>

                @if ($contacts->hasPages())
                    <div>{{ $contacts->links() }}</div>
                @endif
            @endif
        </div>

        {{-- Edits proposed by Managers to existing contacts --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Edits awaiting approval</h2>
                    <p class="text-sm text-muted-foreground">
                        Changes proposed by Managers to existing contacts. Nothing takes effect until approved.
                    </p>
                </div>
                <x-ui.badge class="text-sm px-3 py-1">{{ $editRequests->total() }} pending</x-ui.badge>
            </div>

            @if ($editRequests->isEmpty())
                <x-ui.card>
                    <x-ui.card-content class="py-12 text-center">
                        <p class="text-sm text-muted-foreground">No edits waiting for approval.</p>
                    </x-ui.card-content>
                </x-ui.card>
            @else
                <div class="space-y-3">
                    @foreach ($editRequests as $editRequest)
                        <x-ui.card>
                            <x-ui.card-content class="p-4 space-y-3">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <x-ui.avatar :name="$editRequest->contact->name" size="md" />
                                        <div>
                                            <a href="{{ route('contacts.show', $editRequest->contact) }}" class="font-semibold text-base hover:underline">{{ $editRequest->contact->name }}</a>
                                            <div class="text-xs text-muted-foreground">
                                                Proposed by <span class="font-medium text-foreground">{{ $editRequest->requestedBy?->name ?? '—' }}</span>
                                                · {{ $editRequest->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    @can('approve-edits')
                                        <div class="flex items-center gap-2 shrink-0">
                                            <form method="POST" action="{{ route('contact-edits.approve', $editRequest) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-green-600 px-3 text-xs font-medium text-white hover:bg-green-700 transition-colors">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('contact-edits.reject', $editRequest) }}" onsubmit="return confirm('Reject this edit?')">
                                                @csrf
                                                <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-md bg-red-600 px-3 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                                    ✕ Reject
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>

                                <div class="rounded-md border border-input divide-y text-sm">
                                    @foreach ($editRequest->changes as $field => $newValue)
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 p-2">
                                            <div class="text-xs font-medium text-muted-foreground uppercase tracking-wide">{{ str_replace('_', ' ', $field) }}</div>
                                            <div class="text-muted-foreground line-through decoration-destructive/60">{{ \Illuminate\Support\Str::limit((string) ($editRequest->original[$field] ?? ''), 80) ?: '(empty)' }}</div>
                                            <div class="font-medium">{{ \Illuminate\Support\Str::limit((string) $newValue, 80) ?: '(empty)' }}</div>
                                        </div>
                                    @endforeach
                                    @if ($editRequest->tags !== null)
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 p-2">
                                            <div class="text-xs font-medium text-muted-foreground uppercase tracking-wide">Tags</div>
                                            <div class="sm:col-span-2 text-muted-foreground">Will be set to: {{ \App\Models\Tag::whereIn('id', $editRequest->tags)->pluck('name')->implode(', ') ?: '(none)' }}</div>
                                        </div>
                                    @endif
                                    @if ($editRequest->photo_path)
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 p-2">
                                            <div class="text-xs font-medium text-muted-foreground uppercase tracking-wide">Photo</div>
                                            <div class="sm:col-span-2 text-muted-foreground">A new profile photo was uploaded.</div>
                                        </div>
                                    @endif
                                </div>
                            </x-ui.card-content>
                        </x-ui.card>
                    @endforeach
                </div>

                @if ($editRequests->hasPages())
                    <div>{{ $editRequests->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
