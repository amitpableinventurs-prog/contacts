<x-app-layout>
    <x-slot:header>Contacts / {{ $contact->name }}</x-slot:header>

    <div class="space-y-4">

        @can('manage', $contact)
        @if (($duplicateCount ?? 0) > 0)
            <x-ui.card class="border-warning/40 bg-warning/5">
                <x-ui.card-content class="p-4 flex items-center gap-3 text-sm">
                    <svg class="h-4 w-4 shrink-0 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                    <div class="flex-1">
                        <span class="font-medium">{{ $duplicateCount }} potential {{ \Illuminate\Support\Str::plural('duplicate', $duplicateCount) }}</span>
                        <span class="text-muted-foreground">— matching email or phone.</span>
                    </div>
                    <a href="{{ route('contacts.merge', $contact) }}"><x-ui.button size="sm" variant="outline">Review &amp; merge</x-ui.button></a>
                </x-ui.card-content>
            </x-ui.card>
        @endif
        @endcan

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <a href="{{ route('contacts.index') }}" class="hover:text-foreground">Contacts</a>
                <span>/</span>
                <span class="text-foreground">{{ $contact->name }}</span>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $telNumber = $contact->phone ?: $contact->number;
                    $waDigits  = $contact->phone_digits ?: preg_replace('/\D+/', '', (string) $telNumber);
                    if (strlen($waDigits) === 10) {
                        $waDigits = '91'.$waDigits;
                    }
                @endphp
                @if (auth()->user()->isClerk())
                    @if ($telNumber)
                        <a href="tel:{{ $telNumber }}" title="Call"
                           class="rounded-md border border-input bg-background p-2 hover:bg-accent text-muted-foreground hover:text-foreground">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </a>
                    @endif
                    @if ($waDigits)
                        <a href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener" title="WhatsApp"
                           class="rounded-md border border-input bg-background p-2 hover:bg-accent text-green-600 hover:text-green-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    @endif
                    @if ($contact->email)
                        <a href="mailto:{{ $contact->email }}" title="Email"
                           class="rounded-md border border-input bg-background p-2 hover:bg-accent text-muted-foreground hover:text-foreground">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </a>
                    @endif
                @endif
                @if (($contact->phone ?: $contact->number) && !auth()->user()->isClerk())
                    <form method="POST" action="{{ route('calls.log') }}" class="inline">
                        @csrf
                        <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
                        <x-ui.button variant="outline" size="sm" type="submit">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Call
                        </x-ui.button>
                    </form>
                    <a href="{{ route('sms.show', $contact) }}">
                        <x-ui.button variant="outline" size="sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            SMS
                        </x-ui.button>
                    </a>
                    @if ($waDigits)
                        <a href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener">
                            <x-ui.button variant="outline" size="sm" class="text-green-600 hover:text-green-700">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </x-ui.button>
                        </a>
                    @endif
                    @if ($contact->email)
                        <a href="mailto:{{ $contact->email }}">
                            <x-ui.button variant="outline" size="sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Email
                            </x-ui.button>
                        </a>
                    @endif
                @endif
                @can('update', $contact)
                    <a href="{{ route('contacts.edit', $contact) }}"><x-ui.button size="sm">Edit</x-ui.button></a>
                @endcan
                <x-ui.dropdown-menu align="end">
                    <x-slot:trigger>
                        <button class="rounded-md border border-input bg-background h-9 px-3 hover:bg-accent">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                        </button>
                    </x-slot:trigger>
                    @can('manage', $contact)
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
                    @endcan
                    @can('reactivate', $contact)
                        @if (in_array($contact->status, ['banned', 'suspended'], true))
                            <form method="POST" action="{{ route('contacts.reactivate', $contact) }}">
                                @csrf
                                <x-ui.dropdown-menu-item as="button" type="submit">Reactivate</x-ui.dropdown-menu-item>
                            </form>
                        @endif
                    @endcan
                    @can('delete', $contact)
                        <x-ui.dropdown-menu-separator />
                        @if (auth()->user()->isSuperAdmin())
                            <form method="POST" action="{{ route('contacts.destroy', $contact) }}" onsubmit="return confirmDeleteWithPin(this, 'Move {{ addslashes($contact->name) }} to trash?')">
                                @csrf @method('DELETE')
                                <input type="hidden" name="pin" value="" />
                                <x-ui.dropdown-menu-item as="button" type="submit" destructive>🗑 Move to trash</x-ui.dropdown-menu-item>
                            </form>
                        @else
                            <form method="POST" action="{{ route('contacts.destroy', $contact) }}" onsubmit="return confirm('Move {{ addslashes($contact->name) }} to trash?')">
                                @csrf @method('DELETE')
                                <x-ui.dropdown-menu-item as="button" type="submit" destructive>🗑 Move to trash</x-ui.dropdown-menu-item>
                            </form>
                        @endif
                    @endcan
                </x-ui.dropdown-menu>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Left rail --}}
            <x-ui.card class="lg:col-span-1 h-fit">
                <x-ui.card-content class="p-6 space-y-4">
                    <div class="flex flex-col items-center text-center">
                        <x-ui.avatar :name="$contact->name" :src="$contact->photo ? asset('storage/'.$contact->photo) : null" size="xl" />
                        <h2 class="mt-3 text-lg font-semibold">{{ $contact->name }}</h2>
                        @if ($contact->job_title || $contact->company)
                            <p class="text-sm text-muted-foreground">{{ collect([$contact->job_title, $contact->company])->filter()->join(' · ') }}</p>
                        @endif

                        {{-- Status badge --}}
                        @if ($contact->status === 'suspended')
                            <span class="mt-1 inline-flex items-center rounded-md bg-orange-100 px-2 py-0.5 text-xs font-medium text-orange-800">Suspended</span>
                        @elseif ($contact->status === 'banned')
                            <span class="mt-1 inline-flex items-center rounded-md bg-red-700 px-2 py-0.5 text-xs font-semibold text-white">Banned</span>
                        @endif

                        <div class="mt-2 flex flex-wrap items-center justify-center gap-1.5">
                            @if ($contact->lifecycle_stage)
                                <x-ui.badge variant="secondary">{{ ucfirst($contact->lifecycle_stage) }}</x-ui.badge>
                            @endif
                            @if ($contact->group)
                                <x-ui.badge variant="outline">
                                    <span class="h-1.5 w-1.5 rounded-full mr-1" style="background:{{ $contact->group->color ?: '#a855f7' }}"></span>
                                    {{ $contact->group->name }}
                                </x-ui.badge>
                            @endif
                        </div>
                    </div>

                    {{-- Star rating display + quick rate --}}
                    <div class="text-center">
                        @php $r = (float)($contact->rating ?? 0); @endphp
                        <div class="flex items-center justify-center gap-0.5 mb-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= $r ? 'text-yellow-400 fill-yellow-400' : 'text-muted-foreground fill-transparent' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-muted-foreground">{{ $r > 0 ? number_format($r, 1).'/5' : 'Not rated' }}</p>
                    </div>

                    <x-ui.separator />

                    <dl class="space-y-3 text-sm">
                        @if ($contact->email)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Email</dt>
                            <dd><a href="mailto:{{ $contact->email }}" class="hover:underline">{{ $contact->email }}</a></dd></div>
                        @endif
                        @php $phone = $contact->phone ?: $contact->number; @endphp
                        @if ($phone)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Phone</dt><dd>{{ $phone }}</dd></div>
                        @endif
                        @if ($contact->admin_comment)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Comment</dt>
                            <dd class="mt-1 rounded-md border border-yellow-300 bg-yellow-50 px-2 py-1.5 text-yellow-800 whitespace-pre-line">{{ $contact->admin_comment }}</dd></div>
                        @endif
                        @if ($contact->website)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Website</dt>
                            <dd><a href="{{ $contact->website }}" target="_blank" rel="noopener" class="hover:underline truncate block">{{ $contact->website }}</a></dd></div>
                        @endif
                        @if ($contact->address)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Address</dt><dd>{{ $contact->address }}</dd></div>
                        @endif
                        @if ($contact->city)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">City</dt><dd>{{ $contact->city }}</dd></div>
                        @endif
                        @if ($contact->birthday)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Birthday</dt><dd>{{ $contact->birthday->format('M j, Y') }}</dd></div>
                        @endif
                        <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Last contacted</dt><dd>{{ $contact->last_contacted_at?->diffForHumans() ?? '—' }}</dd></div>
                        @if ($contact->owner)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Owner</dt><dd>{{ $contact->owner->name }}</dd></div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-muted-foreground">Created</dt>
                            <dd title="{{ $contact->created_at->format('d M Y H:i:s') }}">{{ $contact->created_at->format('d M Y, h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-muted-foreground">Last Updated</dt>
                            <dd title="{{ $contact->updated_at->format('d M Y H:i:s') }}">{{ $contact->updated_at->diffForHumans() }} <span class="text-muted-foreground text-xs">({{ $contact->updated_at->format('d M Y') }})</span></dd>
                        </div>
                    </dl>

                    @if ($contact->tags->isNotEmpty())
                        <x-ui.separator />
                        <div>
                            <div class="text-xs uppercase tracking-wide text-muted-foreground mb-2">Tags</div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($contact->tags as $tag)
                                    <x-ui.badge variant="outline">{{ $tag->name }}</x-ui.badge>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($contact->facebook || $contact->twitter || $contact->linkedin)
                        <x-ui.separator />
                        <div>
                            <div class="text-xs uppercase tracking-wide text-muted-foreground mb-2">Social</div>
                            <div class="space-y-1 text-sm">
                                @if ($contact->twitter)  <div>🐦 {{ $contact->twitter }}</div> @endif
                                @if ($contact->linkedin) <div>💼 {{ $contact->linkedin }}</div> @endif
                                @if ($contact->facebook) <div>📘 {{ $contact->facebook }}</div> @endif
                            </div>
                        </div>
                    @endif
                </x-ui.card-content>
            </x-ui.card>

            {{-- Right column tabs --}}
            <div class="lg:col-span-2">
                <x-ui.tabs default="activity">
                    <x-ui.tabs-list>
                        <x-ui.tabs-trigger value="activity">Activity</x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="notes">Notes ({{ $contact->contactNotes->count() }})</x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="history">History ({{ $contact->editHistories->count() }})</x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="description">Description</x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="files">Files ({{ $contact->files->count() }})</x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="gallery">Gallery ({{ $contact->galleryImages->count() }})</x-ui.tabs-trigger>
                        <x-ui.tabs-trigger value="custom">Custom fields</x-ui.tabs-trigger>
                    </x-ui.tabs-list>

                    {{-- Activity --}}
                    <x-ui.tabs-content value="activity">
                        <x-ui.card>
                            <x-ui.card-content class="p-0">
                                @php $combined = $activity->concat($emails)->sortByDesc(fn ($i) => $i->sent_at ?? $i->created_at); @endphp
                                @if ($combined->isEmpty())
                                    <p class="text-sm text-muted-foreground py-12 text-center">No activity yet.</p>
                                @else
                                    <ul class="divide-y">
                                        @foreach ($combined as $item)
                                            @php
                                                $isMessage = $item instanceof \App\Models\Message;
                                                $type = $isMessage ? $item->channel : 'email';
                                                $verb = match(true) {
                                                    $type === 'sms' && ($item->direction ?? '') === 'outbound' => 'Sent SMS',
                                                    $type === 'sms' => 'Received SMS',
                                                    $type === 'voice' => 'Called',
                                                    default => 'Emailed',
                                                };
                                                $preview = $isMessage ? $item->body : $item->subject;
                                                $when = $item->sent_at ?? $item->created_at;
                                            @endphp
                                            <li class="flex items-start gap-3 p-4">
                                                <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-muted text-xs font-medium">
                                                    {{ $type === 'sms' ? 'SMS' : ($type === 'voice' ? '☎' : '✉') }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-baseline justify-between gap-3">
                                                        <p class="text-sm font-medium">{{ $verb }}</p>
                                                        <span class="text-xs text-muted-foreground shrink-0">{{ $when?->diffForHumans() }}</span>
                                                    </div>
                                                    @if ($preview)
                                                        <p class="text-sm text-muted-foreground truncate">{{ $preview }}</p>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </x-ui.card-content>
                        </x-ui.card>
                    </x-ui.tabs-content>

                    {{-- Edit History --}}
                    <x-ui.tabs-content value="history">
                        <x-ui.card>
                            <x-ui.card-header>
                                <x-ui.card-title>Edit History</x-ui.card-title>
                                <x-ui.card-description>Last 5 changes to this contact.</x-ui.card-description>
                            </x-ui.card-header>
                            <x-ui.card-content class="p-0">
                                @if ($contact->editHistories->isEmpty())
                                    <p class="text-sm text-muted-foreground py-10 text-center">No edits recorded yet.</p>
                                @else
                                    <ul class="divide-y">
                                        @foreach ($contact->editHistories as $history)
                                            <li class="p-4 space-y-2">
                                                <div class="flex items-center justify-between text-xs text-muted-foreground">
                                                    <span class="font-medium text-foreground">{{ $history->user?->name ?? 'Unknown user' }}</span>
                                                    <span title="{{ $history->created_at->format('d M Y H:i:s') }}">{{ $history->created_at->format('d M Y, h:i A') }}</span>
                                                </div>
                                                <ul class="space-y-1">
                                                    @foreach ($history->changed_fields as $field => $change)
                                                        <li class="text-xs flex flex-wrap items-baseline gap-x-1.5">
                                                            <span class="font-semibold capitalize">{{ str_replace('_', ' ', $field) }}:</span>
                                                            @if ($change['from'])
                                                                <span class="text-red-600 line-through">{{ $change['from'] }}</span>
                                                                <span class="text-muted-foreground">→</span>
                                                            @endif
                                                            <span class="text-green-700">{{ $change['to'] ?? '(cleared)' }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </x-ui.card-content>
                        </x-ui.card>
                    </x-ui.tabs-content>

                    {{-- Notes: everyone (including Clerks) can view and add; only Manager+ can delete --}}
                    <x-ui.tabs-content value="notes">
                        <div class="space-y-3">
                            {{-- Add note form --}}
                            @can('addNote', $contact)
                            <x-ui.card>
                                <x-ui.card-header>
                                    <x-ui.card-title>
                                        @if (auth()->user()->isClerk())
                                            Add or edit your notes
                                        @else
                                            Add note
                                        @endif
                                    </x-ui.card-title>
                                    @if (auth()->user()->isClerk())
                                        <x-ui.card-description>You can add notes and edit your own notes to track interactions and observations.</x-ui.card-description>
                                    @endif
                                </x-ui.card-header>
                                <x-ui.card-content>
                                    <form method="POST" action="{{ route('contacts.notes.store', $contact) }}" class="space-y-3">
                                        @csrf
                                        <x-ui.textarea name="note_html" rows="3" placeholder="Add a note about this contact..." required></x-ui.textarea>
                                        @error('note_html') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                                        <x-ui.button type="submit" size="sm">Save note</x-ui.button>
                                    </form>
                                </x-ui.card-content>
                            </x-ui.card>
                            @endcan

                            {{-- Quick notes field (plain text column) --}}
                            @if ($contact->getAttributes()['notes'] ?? null)
                                <x-ui.card>
                                    <x-ui.card-header><x-ui.card-title class="text-sm">Quick notes</x-ui.card-title></x-ui.card-header>
                                    <x-ui.card-content>
                                        <p class="text-sm whitespace-pre-line leading-relaxed">{{ $contact->getAttributes()['notes'] }}</p>
                                    </x-ui.card-content>
                                </x-ui.card>
                            @endif

                            {{-- Relational notes --}}
                            @forelse ($contact->contactNotes->sortByDesc('created_at') as $note)
                                <x-ui.card x-data="{ editing: false }">
                                    <x-ui.card-content class="p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-center gap-2 text-xs text-muted-foreground mb-2">
                                                <x-ui.avatar :name="$note->author?->name ?? 'User'" size="xs" />
                                                <span>{{ $note->author?->name ?? 'Unknown' }}</span>
                                                <span>·</span>
                                                <span>{{ $note->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @can('editNote', $note)
                                                    <button type="button" @click="editing = !editing" class="text-muted-foreground hover:text-foreground" title="Edit note">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                @endcan
                                                @can('manage', $contact)
                                                    <form method="POST" action="{{ route('contacts.notes.destroy', [$contact, $note]) }}">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-muted-foreground hover:text-destructive" title="Delete note">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                        <p x-show="!editing" class="text-sm whitespace-pre-line">{!! nl2br(e($note->note_html)) !!}</p>
                                        @can('editNote', $note)
                                            <form method="POST" action="{{ route('contacts.notes.update', [$contact, $note]) }}" x-show="editing" x-cloak class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <x-ui.textarea name="note_html" rows="3" required>{{ $note->note_html }}</x-ui.textarea>
                                                @error('note_html') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                                                <div class="flex gap-2">
                                                    <x-ui.button type="submit" size="sm">Save</x-ui.button>
                                                    <button type="button" @click="editing = false" class="inline-flex h-8 items-center justify-center rounded-md border border-input bg-background px-3 text-xs font-medium shadow-sm transition-colors hover:bg-accent focus-ring">Cancel</button>
                                                </div>
                                            </form>
                                        @endcan
                                    </x-ui.card-content>
                                </x-ui.card>
                            @empty
                                <p class="text-sm text-muted-foreground text-center py-8">No notes yet.</p>
                            @endforelse
                        </div>
                    </x-ui.tabs-content>

                    {{-- Description --}}
                    <x-ui.tabs-content value="description">
                        <x-ui.card>
                            <x-ui.card-content class="p-6">
                                @if ($contact->description_html)
                                    <div class="prose prose-sm max-w-none">{!! $contact->description_html !!}</div>
                                @else
                                    <p class="text-sm text-muted-foreground italic">No description added. Use the Edit form to add one.</p>
                                @endif
                            </x-ui.card-content>
                        </x-ui.card>
                    </x-ui.tabs-content>

                    {{-- Files --}}
                    <x-ui.tabs-content value="files">
                        <div class="space-y-3">
                            @can('manage', $contact)
                                <x-ui.card>
                                    <x-ui.card-header><x-ui.card-title>Upload file</x-ui.card-title></x-ui.card-header>
                                    <x-ui.card-content>
                                        <form method="POST" action="{{ route('contacts.files.store', $contact) }}" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                                            @csrf
                                            <div class="flex-1 space-y-1.5">
                                                <input type="file" name="file" required class="block w-full text-sm text-muted-foreground file:mr-3 file:rounded-md file:border file:border-input file:bg-background file:px-3 file:py-1 file:text-sm file:font-medium file:cursor-pointer hover:file:bg-accent" />
                                                @error('file') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                                            </div>
                                            <x-ui.button type="submit" size="sm">Upload</x-ui.button>
                                        </form>
                                    </x-ui.card-content>
                                </x-ui.card>
                            @endcan

                            @forelse ($contact->files->sortByDesc('created_at') as $file)
                                <x-ui.card>
                                    <x-ui.card-content class="p-4 flex items-center gap-3">
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-muted">
                                            <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate">{{ $file->file_name }}</p>
                                            <p class="text-xs text-muted-foreground">{{ number_format($file->size_bytes / 1024, 1) }} KB · {{ $file->created_at->diffForHumans() }}</p>
                                        </div>
                                        <a href="{{ asset('storage/'.$file->file_path) }}" download="{{ $file->file_name }}"
                                           class="text-xs text-muted-foreground hover:text-foreground px-2 py-1 rounded border border-input hover:bg-accent">Download</a>
                                        @can('manage', $contact)
                                            <form method="POST" action="{{ route('contacts.files.destroy', [$contact, $file]) }}" onsubmit="return confirm('Delete file?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-muted-foreground hover:text-destructive">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </x-ui.card-content>
                                </x-ui.card>
                            @empty
                                <p class="text-sm text-muted-foreground text-center py-8">No files uploaded.</p>
                            @endforelse
                        </div>
                    </x-ui.tabs-content>

                    {{-- Gallery --}}
                    <x-ui.tabs-content value="gallery">
                        <div class="space-y-3">
                            @can('manage', $contact)
                                <x-ui.card>
                                    <x-ui.card-header><x-ui.card-title>Upload images</x-ui.card-title></x-ui.card-header>
                                    <x-ui.card-content>
                                        <form method="POST" action="{{ route('contacts.gallery.store', $contact) }}" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                                            @csrf
                                            <div class="flex-1 space-y-1.5">
                                                <input type="file" name="images[]" multiple accept="image/*" required class="block w-full text-sm text-muted-foreground file:mr-3 file:rounded-md file:border file:border-input file:bg-background file:px-3 file:py-1 file:text-sm file:font-medium file:cursor-pointer hover:file:bg-accent" />
                                                @error('images') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                                            </div>
                                            <x-ui.button type="submit" size="sm">Upload</x-ui.button>
                                        </form>
                                    </x-ui.card-content>
                                </x-ui.card>
                            @endcan

                            @if ($contact->galleryImages->isNotEmpty())
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach ($contact->galleryImages->sortByDesc('created_at') as $image)
                                        <div class="relative group rounded-lg overflow-hidden border border-input bg-muted aspect-square">
                                            <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->image_name }}" class="w-full h-full object-cover" />
                                            @can('manage', $contact)
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <form method="POST" action="{{ route('contacts.gallery.destroy', [$contact, $image]) }}" onsubmit="return confirm('Delete image?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="bg-destructive text-destructive-foreground rounded-md px-3 py-1.5 text-xs font-medium">Delete</button>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-muted-foreground text-center py-8">No images in gallery.</p>
                            @endif
                        </div>
                    </x-ui.tabs-content>

                    {{-- Custom fields --}}
                    <x-ui.tabs-content value="custom">
                        <x-ui.card>
                            <x-ui.card-content class="p-6">
                                @if (!empty($contact->custom_fields))
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        @foreach ($contact->custom_fields as $key => $value)
                                            <div>
                                                <dt class="text-xs uppercase tracking-wide text-muted-foreground">{{ $key }}</dt>
                                                <dd>{{ is_scalar($value) ? $value : json_encode($value) }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @else
                                    <p class="text-sm text-muted-foreground italic">No custom fields set.</p>
                                @endif
                            </x-ui.card-content>
                        </x-ui.card>
                    </x-ui.tabs-content>
                </x-ui.tabs>
            </div>
        </div>
    </div>

    @if (auth()->user()->isSuperAdmin())
        @push('scripts')
        <script>
            // Super Admin's delete action requires the export/import PIN —
            // validated again server-side, this just avoids a round trip.
            function confirmDeleteWithPin(form, message) {
                if (!confirm(message)) return false;
                const pin = prompt('Enter PIN to confirm deletion:');
                if (!pin) return false;
                form.querySelector('input[name="pin"]').value = pin;
                return true;
            }
        </script>
        @endpush
    @endif
</x-app-layout>
