@props(['align' => 'end', 'width' => 'w-56'])

@php
$origin = match ($align) {
    'start' => 'origin-top-left',
    'center' => 'origin-top -translate-x-1/2',
    default => 'origin-top-right',
};
@endphp

{{-- The panel uses fixed positioning (computed from the trigger) so it can
     never be clipped by overflow-auto ancestors such as table wrappers. --}}
<div x-data="{
        open: false,
        menuStyle: '',
        toggle() {
            if (this.open) { this.open = false; return; }
            const r = this.$el.getBoundingClientRect();
            let s = (window.innerHeight - r.bottom < 260 && r.top > window.innerHeight - r.bottom)
                ? 'bottom:' + Math.round(window.innerHeight - r.top + 6) + 'px;'
                : 'top:' + Math.round(r.bottom + 6) + 'px;';
            @if ($align === 'start')
                s += 'left:' + Math.max(8, Math.round(r.left)) + 'px;';
            @elseif ($align === 'center')
                s += 'left:' + Math.round(r.left + r.width / 2) + 'px;';
            @else
                s += 'right:' + Math.max(8, Math.round(window.innerWidth - r.right)) + 'px;';
            @endif
            this.menuStyle = s;
            this.open = true;
        },
     }"
     @keydown.escape.window="open = false"
     @scroll.window.passive="open = false"
     @resize.window.passive="open = false"
     class="relative inline-block text-left">
    <div @click="toggle()">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-cloak
         :style="menuStyle"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed z-50 {{ $width }} {{ $origin }} max-h-[70vh] overflow-y-auto rounded-md border bg-popover text-popover-foreground shadow-lg p-1 outline-none">
        {{ $slot }}
    </div>
</div>
