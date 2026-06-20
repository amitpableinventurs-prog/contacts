@props(['default' => ''])

<div x-data="{ active: '{{ $default }}' }" {{ $attributes }}>
    {{ $slot }}
</div>
