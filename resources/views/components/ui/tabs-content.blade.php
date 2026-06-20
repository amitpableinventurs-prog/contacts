@props(['value'])

<div x-show="active === '{{ $value }}'"
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     role="tabpanel"
     {{ $attributes->class(['mt-4 focus-ring']) }}>
    {{ $slot }}
</div>
