@props(['orientation' => 'horizontal'])

<div {{ $attributes->class([
    'shrink-0 bg-border',
    'h-px w-full' => $orientation === 'horizontal',
    'h-full w-px' => $orientation === 'vertical',
]) }} role="separator"></div>
