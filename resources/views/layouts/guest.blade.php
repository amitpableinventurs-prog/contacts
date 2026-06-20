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
    <title>{{ $brand->app_name }}</title>
    <style>:root, html.dark { --primary: {{ $primaryHsl }}; --ring: {{ $primaryHsl }}; }</style>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

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
</head>
<body class="min-h-screen bg-background text-foreground antialiased">

<div class="grid min-h-screen lg:grid-cols-2">

    {{-- Brand panel (desktop) --}}
    <div class="hidden lg:flex relative flex-col justify-between p-10 text-primary-foreground bg-gradient-to-br from-primary via-primary/90 to-primary/70">
        <div class="absolute inset-0 opacity-20 pointer-events-none"
             style="background-image: radial-gradient(circle at 20% 0%, white 0, transparent 30%), radial-gradient(circle at 80% 100%, white 0, transparent 30%);"></div>

        <div class="relative flex items-center gap-2 font-semibold">
            @if ($brand->logo_path)
                <img src="{{ asset('storage/'.$brand->logo_path) }}" alt="{{ $brand->app_name }}" class="h-8 w-8 rounded-lg bg-white/10 object-contain p-0.5 backdrop-blur border border-white/20" />
            @else
                <div class="grid place-items-center h-8 w-8 rounded-lg bg-white/10 backdrop-blur border border-white/20">{{ mb_substr($brand->app_name, 0, 1) }}</div>
            @endif
            {{ $brand->app_name }}
        </div>

        <div class="relative">
            <blockquote class="space-y-2">
                <p class="text-xl leading-relaxed">{{ $brand->app_description }}</p>
                <footer class="text-sm opacity-80">{{ $brand->app_name }}</footer>
            </blockquote>
        </div>
    </div>

    {{-- Form panel --}}
    <div class="flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-sm space-y-6">
            <div class="flex justify-center lg:hidden">
                <div class="flex items-center gap-2 font-semibold">
                    @if ($brand->logo_path)
                        <img src="{{ asset('storage/'.$brand->logo_path) }}" alt="{{ $brand->app_name }}" class="h-8 w-8 rounded-lg object-contain" />
                    @else
                        <div class="grid place-items-center h-8 w-8 rounded-lg bg-primary text-primary-foreground">{{ mb_substr($brand->app_name, 0, 1) }}</div>
                    @endif
                    {{ $brand->app_name }}
                </div>
            </div>

            {{ $slot }}
        </div>
    </div>
</div>

</body>
</html>
