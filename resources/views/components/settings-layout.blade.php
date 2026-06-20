@props(['active' => 'general'])

@php
$tabs = [
    ['key' => 'general',  'label' => 'General',  'route' => 'settings.general'],
    ['key' => 'branding', 'label' => 'Branding', 'route' => 'settings.branding'],
    ['key' => 'mail',     'label' => 'Mail',     'route' => 'settings.mail'],
    ['key' => 'twilio',   'label' => 'Twilio',   'route' => 'settings.twilio'],
    ['key' => 'ai',       'label' => 'AI',       'route' => 'settings.ai'],
];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-[200px_1fr] gap-6">
    <nav class="space-y-1">
        @foreach ($tabs as $tab)
            @php $isActive = $tab['key'] === $active; @endphp
            <a href="{{ route($tab['route']) }}"
               @class([
                   'block rounded-md px-3 py-2 text-sm font-medium transition-colors',
                   'bg-accent text-accent-foreground' => $isActive,
                   'text-muted-foreground hover:bg-accent/60 hover:text-foreground' => !$isActive,
               ])>
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="min-w-0">
        {{ $slot }}
    </div>
</div>
