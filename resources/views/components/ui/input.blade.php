@props(['type' => 'text'])

<input type="{{ $type }}"
    {{ $attributes->class([
        'flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors',
        'placeholder:text-muted-foreground',
        'focus-ring',
        'disabled:cursor-not-allowed disabled:opacity-50',
        'file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground',
    ]) }} />
