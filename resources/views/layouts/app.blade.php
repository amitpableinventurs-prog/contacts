@php
$brand = app(\App\Settings\GeneralSettings::class);
$primaryHsl = \App\Support\ColorHelper::hexToHslVar($brand->primary_color);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? $brand->app_name }}</title>
    <style>:root, html.dark { --primary: {{ $primaryHsl }}; --ring: {{ $primaryHsl }}; }</style>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Set theme before paint to avoid FOUC --}}
    <script>
        (function() {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-background text-foreground antialiased">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    <aside class="hidden lg:flex w-64 flex-col border-r bg-card">
        <x-app.sidebar />
    </aside>

    {{-- Mobile sidebar (slide-over) --}}
    <div x-show="sidebarOpen" x-cloak class="lg:hidden">
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-background/80 backdrop-blur-sm"></div>
        <aside x-show="sidebarOpen"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r bg-card">
            <x-app.sidebar />
        </aside>
    </div>

    {{-- Main column --}}
    <div class="flex flex-1 flex-col min-w-0">
        {{-- Top bar --}}
        <header class="sticky top-0 z-30 flex h-14 items-center gap-3 border-b bg-background/80 px-4 backdrop-blur lg:px-6">
            <button type="button" @click="sidebarOpen = true" class="lg:hidden -ml-1 rounded-md p-2 hover:bg-accent">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <span class="sr-only">Open sidebar</span>
            </button>

            <div class="flex-1 min-w-0">
                @isset($header)
                    <div class="text-sm text-muted-foreground truncate">{{ $header }}</div>
                @endisset
            </div>

            <x-app.command-palette />
            <x-app.dark-mode-toggle />
            <x-app.user-menu />
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

<x-ui.toast />

@auth
    @if (auth()->user()->current_team_id)
    <script>
        (function() {
            const subscribe = () => {
                if (!window.Echo) { setTimeout(subscribe, 100); return; }
                window.Echo.private('team.{{ auth()->user()->current_team_id }}')
                    .listen('MessageReceived', (e) => {
                        const who = e.contact?.name || e.from_number || 'someone';
                        const channel = (e.channel || 'sms').toUpperCase();
                        window.dispatchEvent(new CustomEvent('toast', { detail: {
                            type: 'default',
                            title: 'New ' + channel + ' from ' + who,
                            message: (e.body || '').slice(0, 140),
                            duration: 8000,
                        }}));
                        const tag = document.title.startsWith('•') ? document.title : '• ' + document.title;
                        document.title = tag;
                    });
            };
            subscribe();
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) document.title = document.title.replace(/^•\s*/, '');
            });
        })();
    </script>
    @endif
@endauth

@stack('scripts')

</body>
</html>
