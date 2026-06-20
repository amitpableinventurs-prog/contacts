@props(['src' => null, 'name' => '', 'size' => 'default'])

@php
$src = $src
    ? (str_starts_with($src, 'http') ? $src : asset('storage/'.$src))
    : null;

$sizes = [
    'sm' => 'h-7 w-7 text-xs',
    'default' => 'h-9 w-9 text-sm',
    'lg' => 'h-12 w-12 text-base',
    'xl' => 'h-20 w-20 text-2xl',
];
$initials = collect(preg_split('/\s+/', trim((string) $name)))
    ->filter()
    ->take(2)
    ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
    ->implode('') ?: '?';
@endphp

<span {{ $attributes->class([
    'relative inline-flex shrink-0 overflow-hidden rounded-full bg-muted',
    'items-center justify-center font-medium text-muted-foreground',
    $sizes[$size] ?? $sizes['default'],
]) }}>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="aspect-square h-full w-full object-cover"
             onerror="this.style.display='none';this.nextElementSibling.style.display='inline';" />
        <span style="display:none;">{{ $initials }}</span>
    @else
        <span>{{ $initials }}</span>
    @endif
</span>
