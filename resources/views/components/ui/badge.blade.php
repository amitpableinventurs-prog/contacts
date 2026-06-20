@props(['variant' => 'default'])

@php
$variants = [
    'default' => 'border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80',
    'secondary' => 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
    'destructive' => 'border-transparent bg-destructive text-destructive-foreground shadow hover:bg-destructive/80',
    'success' => 'border-transparent bg-success text-success-foreground shadow hover:bg-success/80',
    'warning' => 'border-transparent bg-warning text-warning-foreground shadow hover:bg-warning/80',
    'outline' => 'text-foreground',
];
$classes = 'inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-semibold transition-colors focus-ring '
    . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->class($classes) }}>
    {{ $slot }}
</span>
