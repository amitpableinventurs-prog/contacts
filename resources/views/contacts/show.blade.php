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

        {{-- Top Bar Header & Actions --}}
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
                        <a href="tel:{{ $telNumber }}" title="Call" class="rounded-md border border-input bg-background p-2 hover:bg-accent text-muted-foreground hover:text-foreground">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </a>
                    @endif
                    @if ($waDigits)
                        <a href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener" title="WhatsApp" class="rounded-md border border-input bg-background p-2 hover:bg-accent text-green-600 hover:text-green-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    @endif
                    @if ($contact->email)
                        <a href="mailto:{{ $contact->email }}" title="Email" class="rounded-md border border-input bg-background p-2 hover:bg-accent text-muted-foreground hover:text-foreground">
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
        </div> {{-- Fixed: Closed top bar header wrapper --}}

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Left rail profile --}}
            <x-ui.card class="lg:col-span-1 h-fit">
                <x-ui.card-content class="p-6 space-y-4">
                    <div class="flex flex-col items-center text-center">
                        <x-ui.avatar :name="$contact->name" :src="$contact->photo ? asset('storage/'.$contact->photo) : null" size="xl" />
                        <h2 class="mt-3 text-lg font-semibold">{{ $contact->name }}</h2>
                        @if ($contact->job_title || $contact->company)
                            <p class="text-sm text-muted-foreground">{{ collect([$contact->job_title, $contact->company])->filter()->join(' · ') }}</p>
                        @endif
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
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-muted-foreground">Email</dt>
                                <dd><a href="mailto:{{ $contact->email }}" class="hover:underline">{{ $contact->email }}</a></dd>
                            </div>
                        @endif
                        @php $phone = $contact->phone ?: $contact->number; @endphp
                        @if ($phone)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Phone</dt><dd>{{ $phone }}</dd></div>
                        @endif
                        @if ($contact->admin_comment)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-muted-foreground">Comment</dt>
                                <dd class="mt-1 rounded-md border border-yellow-300 bg-yellow-50 px-2 py-1.5 text-yellow-800 whitespace-pre-line">{{ $contact->admin_comment }}</dd>
                            </div>
                        @endif
                        @if ($contact->website)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-muted-foreground">Website</dt>
                                <dd><a href="{{ $contact->website }}" target="_blank" rel="noopener" class="hover:underline truncate block">{{ $contact->website }}</a></dd>
                            </div>
                        @endif
                        @if ($contact->address)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Address</dt><dd>{{ $contact->address }}</dd></div>
                        @endif
                        @if ($contact->city)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">City</dt><dd>{{ $contact->city }}</dd></div>
                        @endif
                        @if ($contact->area)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Area</dt><dd>{{ $contact->area }}</dd></div>
                        @endif
                        @if ($contact->gender)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Gender</dt><dd>{{ ucfirst($contact->gender) }}</dd></div>
                        @endif
                        @if ($contact->birthday)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Birthday</dt><dd>{{ $contact->birthday->format('M j, Y') }} <span class="text-muted-foreground">({{ $contact->birthday->age }} years old)</span></dd></div>
                        @endif
                        <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Last contacted</dt><dd>{{ $contact->last_contacted_at?->diffForHumans() ?? '—' }}</dd></div>
                        @if ($contact->owner)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Added by</dt><dd>{{ $contact->owner->name }}</dd></div>
                        @endif
                        @if ($contact->approvedBy)
                            <div><dt class="text-xs uppercase tracking-wide text-muted-foreground">Approved by</dt><dd>{{ $contact->approvedBy->name }}</dd></div>
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
                        </div> {{-- Fixed: Closed container tag --}}
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
                        </div> {{-- Fixed: Closed container tag --}}
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

                    {{-- Activity Tab --}}
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

                    {{-- Notes Tab --}}
                    <x-ui.tabs-content value="notes">
                        <div class="space-y-3">
                            @can('addNote', $contact)
                                <x-ui.card>
                                    <x-ui.card-header>
                                        <x-ui.card-title>{{ auth()->user()->isClerk() ? 'Add or edit your notes' : 'Add note' }}</x-ui.card-title>
                                    </x-ui.card-header>
                                    <x-ui.card-content>
                                        <form method="POST" action="{{ route('contacts.notes.store', $contact) }}" class="space-y-3">
                                            @csrf
                                            <x-ui.textarea name="note_html" rows="3" placeholder="Add a note about this contact..." required></x-ui.textarea>
                                            <x-ui.button type="submit" size="sm">Save note</x-ui.button>
                                        </form>
                                    </x-ui.card-content>
                                </x-ui.card>
                            @endcan

                            @if (!empty($contact->notes))
                                <x-ui.card>
                                    <x-ui.card-header>
                                        <x-ui.card-title class="text-sm">Quick notes</x-ui.card-title>
                                    </x-ui.card-header>
                                    <x-ui.card-content>
                                        <p class="text-sm whitespace-pre-line">{{ $contact->notes }}</p>
                                    </x-ui.card-content>
                                </x-ui.card>
                            @endif
                        </div>
                    </x-ui.tabs-content>
                </x-ui.tabs>
            </div>
        </div>
    </div>
</x-app-layout>