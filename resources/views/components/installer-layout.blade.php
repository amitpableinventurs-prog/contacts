@props(['step' => 1])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Install · Contacts</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Emergency fallback if `npm run build` hasn't been run --}}
        <style>
            :root { --primary: #7c3aed; --bg: #f8fafc; --fg: #0f172a; --muted: #64748b; --border: #e2e8f0; --card: #fff; --success:#16a34a; }
            @media (prefers-color-scheme: dark) { :root { --bg: #020617; --fg: #f8fafc; --muted: #94a3b8; --border: #1e293b; --card: #0f172a; } }
            *{box-sizing:border-box}body{margin:0;font-family:'Inter',-apple-system,sans-serif;background:var(--bg);color:var(--fg);line-height:1.55}
        </style>
    @endif

    <style>
        @keyframes fade-up { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(124,58,237,.4); } 70% { box-shadow: 0 0 0 12px rgba(124,58,237,0); } 100% { box-shadow: 0 0 0 0 rgba(124,58,237,0); } }
        @keyframes shimmer { 0% { background-position: -1000px 0; } 100% { background-position: 1000px 0; } }
        .animate-fade-up { animation: fade-up .55s cubic-bezier(.16,1,.3,1) both; }
        .animate-pulse-ring { animation: pulse-ring 2s infinite; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-background text-foreground antialiased font-sans">

{{-- Decorative gradient background --}}
<div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[900px] h-[900px] rounded-full bg-primary/20 blur-[140px]"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full bg-primary/10 blur-[100px]"></div>
    <div class="absolute top-1/3 -left-40 w-[400px] h-[400px] rounded-full bg-fuchsia-500/10 blur-[100px]"></div>
</div>

<div class="min-h-screen flex flex-col">

    {{-- Top brand bar --}}
    <header class="border-b border-border/40 backdrop-blur-xl bg-background/60 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto flex h-16 items-center justify-between px-6">
            <div class="flex items-center gap-3">
                <div class="grid place-items-center h-9 w-9 rounded-xl bg-gradient-to-br from-primary to-fuchsia-500 text-white font-bold shadow-lg shadow-primary/30">L</div>
                <div>
                    <div class="font-semibold text-sm tracking-tight">Contacts</div>
                    <div class="text-[10px] uppercase tracking-widest text-muted-foreground">Setup wizard</div>
                </div>
            </div>
            <div class="text-xs text-muted-foreground tabular-nums">
                Step <span class="text-foreground font-semibold">{{ $step }}</span> <span class="opacity-50">/</span> 5
            </div>
        </div>
    </header>

    {{-- Stepper --}}
    @php
        $stepLabels = [
            1 => 'Welcome',
            2 => 'Requirements',
            3 => 'Database',
            4 => 'Admin',
            5 => 'Done',
        ];
    @endphp
    <div class="border-b border-border/40 bg-background/60 backdrop-blur-xl">
        <div class="max-w-3xl mx-auto px-6 py-6">
            <div class="flex items-center gap-2 sm:gap-3">
                @foreach ($stepLabels as $n => $label)
                    @php $isDone = $n < $step; $isActive = $n === $step; @endphp
                    <div class="flex flex-col items-center gap-1.5 shrink-0">
                        <div @class([
                            'grid place-items-center h-9 w-9 rounded-full text-xs font-semibold transition-all',
                            'bg-success text-success-foreground shadow-md shadow-success/30' => $isDone,
                            'bg-gradient-to-br from-primary to-fuchsia-500 text-white shadow-lg shadow-primary/40 animate-pulse-ring' => $isActive,
                            'bg-muted text-muted-foreground border border-border' => !$isDone && !$isActive,
                        ])>
                            @if ($isDone)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $n }}
                            @endif
                        </div>
                        <span @class([
                            'text-[10px] uppercase tracking-wider hidden sm:block',
                            'text-foreground font-medium' => $isActive,
                            'text-success' => $isDone,
                            'text-muted-foreground' => !$isDone && !$isActive,
                        ])>{{ $label }}</span>
                    </div>
                    @if ($n < 5)
                        <div @class([
                            'flex-1 h-px transition-colors',
                            'bg-success' => $isDone,
                            'bg-border' => !$isDone,
                        ])></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <main class="flex-1 px-6 py-10 sm:py-14">
        <div class="max-w-2xl mx-auto animate-fade-up">
            {{ $slot }}
        </div>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-border/40 py-5">
        <div class="max-w-5xl mx-auto px-6 flex flex-wrap items-center justify-between gap-3 text-xs text-muted-foreground">
            <div>Contacts v2.0 · <a href="/Documentation/index.html" class="hover:text-foreground hover:underline">Installation guide</a></div>
            <div>Need help? Check the documentation bundled with your purchase.</div>
        </div>
    </footer>
</div>

</body>
</html>
