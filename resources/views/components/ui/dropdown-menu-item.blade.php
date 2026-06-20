@props(['href' => null, 'as' => null, 'destructive' => false, 'type' => 'button'])

@php
$tag = $as ?? ($href ? 'a' : 'button');
$classes = 'relative flex w-full cursor-pointer select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors '
    . ($destructive
        ? 'text-destructive hover:bg-destructive/10 focus:bg-destructive/10'
        : 'hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground');
@endphp

@if ($tag === 'a')
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
