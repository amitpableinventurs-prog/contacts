@props(['align' => 'end', 'width' => 'w-56'])

@php
$alignment = match ($align) {
    'start' => 'left-0 origin-top-left',
    'center' => 'left-1/2 -translate-x-1/2 origin-top',
    default => 'right-0 origin-top-right',
};
@endphp

<div x-data="{ open: false }"
     @keydown.escape.window="open = false"
     class="relative inline-block text-left">
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-cloak
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $width }} {{ $alignment }} rounded-md border bg-popover text-popover-foreground shadow-lg p-1 outline-none">
        {{ $slot }}
    </div>
</div>
