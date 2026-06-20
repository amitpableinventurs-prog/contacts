@props(['name' => 'dialog'])

<div x-data="{ open: false }"
     x-on:open-dialog-{{ $name }}.window="open = true"
     x-on:close-dialog-{{ $name }}.window="open = false"
     @keydown.escape.window="open = false">
    @isset ($trigger)
        <div @click="open = true">{{ $trigger }}</div>
    @endisset

    <template x-teleport="body">
        <div x-show="open"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="open = false"
                 class="fixed inset-0 bg-background/80 backdrop-blur-sm"></div>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 {{ $attributes->class(['relative z-50 grid w-full max-w-lg gap-4 rounded-lg border bg-background p-6 shadow-lg sm:rounded-lg']) }}>

                <button type="button"
                        @click="open = false"
                        class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus-ring disabled:pointer-events-none">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="sr-only">Close</span>
                </button>

                {{ $slot }}
            </div>
        </div>
    </template>
</div>
