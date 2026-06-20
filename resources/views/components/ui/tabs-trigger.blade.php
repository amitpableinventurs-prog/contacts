@props(['value'])

<button type="button" role="tab"
        @click="active = '{{ $value }}'"
        :aria-selected="active === '{{ $value }}'"
        :class="active === '{{ $value }}'
            ? 'bg-background text-foreground shadow'
            : 'text-muted-foreground hover:text-foreground'"
        {{ $attributes->class([
            'inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1 text-sm font-medium',
            'transition-all focus-ring',
        ]) }}>
    {{ $slot }}
</button>
