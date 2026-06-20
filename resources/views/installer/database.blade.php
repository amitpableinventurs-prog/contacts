<x-installer-layout :step="$step">
    <div class="text-center space-y-3 mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Connect a database</h1>
        <p class="text-base text-muted-foreground">Pick a driver and enter credentials. We'll write <code class="font-mono text-xs bg-muted px-1.5 py-0.5 rounded">.env</code> and run migrations after testing the connection.</p>
    </div>

    @if ($errors->any())
        <x-ui.card class="bg-destructive/5 border-destructive/30 mb-6">
            <x-ui.card-content class="p-4 flex items-start gap-3 text-sm">
                <svg class="h-5 w-5 mt-0.5 text-destructive shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z"/></svg>
                <div>
                    <strong class="block text-foreground mb-0.5">Couldn't continue</strong>
                    @foreach ($errors->all() as $error)
                        <span class="text-muted-foreground block">{{ $error }}</span>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>
    @endif

    <form method="POST" action="{{ route('install.database.save') }}" id="db-form">
        @csrf

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Driver</x-ui.card-title>
                <x-ui.card-description>Click a card to choose.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @php
                    $drivers = [
                        ['key' => 'sqlite', 'name' => 'SQLite',   'desc' => 'File-based · zero setup'],
                        ['key' => 'mysql',  'name' => 'MySQL',    'desc' => 'or MariaDB · classic'],
                        ['key' => 'pgsql',  'name' => 'Postgres', 'desc' => 'PostgreSQL · enterprise'],
                    ];
                    $selected = old('driver', 'sqlite');
                    @endphp
                    @foreach ($drivers as $d)
                        <label class="cursor-pointer block">
                            <input type="radio" name="driver" value="{{ $d['key'] }}" class="peer sr-only driver-radio" {{ $selected === $d['key'] ? 'checked' : '' }} />
                            <div class="rounded-lg border-2 border-border bg-card p-4 transition-all hover:border-primary/50 peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:shadow-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold text-sm">{{ $d['name'] }}</span>
                                    <svg class="h-4 w-4 text-primary hidden peer-checked:!block driver-check"
                                         fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                </div>
                                <p class="text-xs text-muted-foreground">{{ $d['desc'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>

        {{-- SQLite fields --}}
        <x-ui.card class="mt-4" id="sqlite-card">
            <x-ui.card-header>
                <x-ui.card-title>SQLite</x-ui.card-title>
                <x-ui.card-description>Single-file database. Perfect for small teams and self-hosted demos.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="space-y-1.5">
                    <x-ui.label for="sqlite-db">Filename</x-ui.label>
                    <x-ui.input id="sqlite-db" name="database" value="{{ old('database', 'database.sqlite') }}" placeholder="database.sqlite" />
                    <p class="text-xs text-muted-foreground">Stored under <code class="font-mono">database/</code>. Created automatically if missing.</p>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        {{-- Server-based DB fields --}}
        <x-ui.card class="mt-4" id="server-card">
            <x-ui.card-header>
                <x-ui.card-title id="server-card-title">MySQL connection</x-ui.card-title>
                <x-ui.card-description>Make sure the database already exists and the user has full privileges.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <x-ui.label for="host">Host</x-ui.label>
                    <x-ui.input id="host" name="host" value="{{ old('host', '127.0.0.1') }}" />
                </div>
                <div class="space-y-1.5">
                    <x-ui.label for="port">Port</x-ui.label>
                    <x-ui.input id="port" name="port" value="{{ old('port', '3306') }}" />
                </div>
                <div class="space-y-1.5 sm:col-span-2">
                    <x-ui.label for="server-db">Database name</x-ui.label>
                    <x-ui.input id="server-db" name="database" value="{{ old('database', 'laracontact') }}" />
                </div>
                <div class="space-y-1.5">
                    <x-ui.label for="username">Username</x-ui.label>
                    <x-ui.input id="username" name="username" autocomplete="off" value="{{ old('username') }}" />
                </div>
                <div class="space-y-1.5">
                    <x-ui.label for="password">Password</x-ui.label>
                    <x-ui.input id="password" name="password" type="password" autocomplete="new-password" />
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="mt-4 bg-primary/5 border-primary/20">
            <x-ui.card-content class="p-4 flex items-start gap-3 text-sm">
                <svg class="h-4 w-4 mt-0.5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <div class="text-muted-foreground">
                    Clicking <strong class="text-foreground">Test &amp; migrate</strong> saves <code class="font-mono text-xs bg-muted px-1 rounded">.env</code>, generates <code class="font-mono text-xs bg-muted px-1 rounded">APP_KEY</code>, tests the connection, then runs all migrations.
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <div class="flex items-center justify-between pt-8">
            <a href="{{ route('install.requirements') }}"
               class="inline-flex h-9 items-center rounded-md border border-input bg-background px-4 text-sm font-medium hover:bg-accent transition-colors">
                ← Back
            </a>
            <button type="submit" id="submit-btn"
                    class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-md text-base font-medium bg-gradient-to-br from-primary to-fuchsia-500 text-white shadow-lg shadow-primary/30 hover:shadow-xl transition-all disabled:opacity-70 disabled:cursor-wait">
                <svg id="submit-icon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="submit-label">Test &amp; migrate</span>
            </button>
        </div>
    </form>

    <script>
        (function () {
            const sqliteCard = document.getElementById('sqlite-card');
            const serverCard = document.getElementById('server-card');
            const serverTitle = document.getElementById('server-card-title');
            const radios = document.querySelectorAll('.driver-radio');
            const sqliteInput = document.getElementById('sqlite-db');
            const serverInputs = ['host', 'port', 'server-db', 'username', 'password'].map((id) => document.getElementById(id));
            const portInput = document.getElementById('port');

            function apply(driver) {
                const isSqlite = driver === 'sqlite';

                // Toggle which card is visible
                sqliteCard.style.display = isSqlite ? '' : 'none';
                serverCard.style.display = isSqlite ? 'none' : '';

                // Disable the inputs in the hidden card so they don't submit / conflict on `name="database"`
                sqliteInput.disabled = !isSqlite;
                serverInputs.forEach((el) => el && (el.disabled = isSqlite));

                // Server card heading
                serverTitle.textContent = driver === 'mysql' ? 'MySQL connection' : 'PostgreSQL connection';

                // Smart port default when switching between MySQL/Postgres
                if (driver === 'mysql' && (portInput.value === '5432' || !portInput.value)) portInput.value = '3306';
                if (driver === 'pgsql' && (portInput.value === '3306' || !portInput.value)) portInput.value = '5432';
            }

            radios.forEach((r) => r.addEventListener('change', () => apply(r.value)));

            // Initial state — pick whichever radio is currently checked (respects old() repopulation)
            const checked = document.querySelector('.driver-radio:checked');
            apply(checked ? checked.value : 'sqlite');

            // Disable submit on click so migrations don't get fired twice
            document.getElementById('db-form').addEventListener('submit', function () {
                const btn = document.getElementById('submit-btn');
                btn.disabled = true;
                document.getElementById('submit-icon').outerHTML =
                    '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">' +
                    '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" class="opacity-25"></circle>' +
                    '<path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" class="opacity-75"></path></svg>';
                document.getElementById('submit-label').textContent = 'Migrating…';
            });
        })();
    </script>
</x-installer-layout>
