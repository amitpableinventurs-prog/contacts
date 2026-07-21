@php
use Illuminate\Support\Facades\Route as R;

$user = auth()->user();
$currentTeam = $user?->currentTeam;
$isClerk     = $user?->isClerk();
$isManager   = $user?->isManager();
$isAdmin     = $user?->isAdmin();
$isSuperAdmin = $user?->isSuperAdmin();
$isAdminPlus  = $isAdmin || $isSuperAdmin;
$isManagerPlus = $isManager || $isAdminPlus;

// Every role currently shares one team record, so its stored name (e.g.
// "Super Admin's Workspace") would show up unchanged no matter who's
// logged in. Label the workspace by the viewer's own role instead.
$workspaceLabel = $user
    ? ucwords(str_replace('_', ' ', $user->role ?? 'user')).'\'s Workspace'
    : ($currentTeam->name ?? 'Workspace');

$nav = array_filter([

    // ── Always visible ───────────────────────────────────────────────────
    [
        'label' => null,
        'items' => array_values(array_filter([
            ['name' => 'Dashboard', 'route' => 'dashboard',
             'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['name' => 'Contacts', 'route' => 'contacts.index',
             'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['name' => 'Inbox', 'route' => 'inbox.index',
             'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            // Pending approvals — visible to manager and above
            $isManagerPlus
                ? ['name' => 'Pending Approvals', 'route' => 'contacts.pending',
                   'icon' => 'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z']
                : null,
            // Team — Manager sees their Clerks; Admin/Super Admin see every Manager + Clerk.
            $isManagerPlus
                ? ['name' => 'Team', 'route' => 'team.index',
                   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z']
                : null,
        ])),
    ],

    // ── Communication — Admin and above only ─────────────────────────────
    $isAdminPlus ? [
        'label' => 'Communication',
        'items' => [
            ['name' => 'Messages', 'route' => 'sms.index',
             'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['name' => 'Calls', 'route' => 'calls.index',
             'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
            ['name' => 'Email', 'route' => 'emails.index',
             'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['name' => 'Bulk sends', 'route' => 'bulk-sends.index',
             'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
        ],
    ] : null,

    // ── Library — Manager and above only (Tags visible to all) ──────────
    [
        'label' => 'Library',
        'items' => array_values(array_filter([
            $isManagerPlus
                ? ['name' => 'Groups', 'route' => 'groups.index',
                   'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10']
                : null,
            $isManagerPlus
                ? ['name' => 'Tags', 'route' => 'tags.index',
                   'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z']
                : null,
            $isManagerPlus
                ? ['name' => 'Reminders', 'route' => 'reminders.index',
                   'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9']
                : null,
        ])),
    ],

    // ── Admin — Mixed per role ───────────────────────────────────────────
    [
        'label' => 'Admin',
        'items' => array_values(array_filter([
            $isManagerPlus
                ? ['name' => 'Users', 'route' => 'users.index',
                   'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z']
                : null,
            // Activity log — Admin+ sees all; Manager sees clerk+manager only
            $isManagerPlus
                ? ['name' => 'Activity log', 'route' => 'activity-logs.index',
                   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2']
                : null,
            // Login logs — Admin+ sees all; Manager sees clerk+manager only
            $isManagerPlus
                ? ['name' => 'Login logs', 'route' => 'login-logs.index',
                   'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z']
                : null,
            // Import / Export — Super Admin only (PIN-protected pages).
            $isSuperAdmin
                ? ['name' => 'Import contacts', 'route' => 'imports.form',
                   'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12']
                : null,
            $isSuperAdmin
                ? ['name' => 'Export contacts', 'route' => 'workspace.export',
                   'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4']
                : null,
            $isAdminPlus
                ? ['name' => 'Settings', 'route' => 'settings.index',
                   'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z']
                : null,
        ])),
    ],

]);

// Remove empty sections (no items or null sections)
$nav = array_values(array_filter($nav, fn ($s) => !empty($s['items'])));

$isActiveRoute = fn (string $route) => R::has($route) && request()->routeIs($route.'*');
$brand = app(\App\Settings\GeneralSettings::class);
@endphp

<div class="flex h-14 items-center gap-2 px-4 border-b">
    @if ($brand->logo_path)
        <img src="{{ asset('storage/'.$brand->logo_path) }}" alt="{{ $brand->app_name }}" class="h-8 w-8 rounded-lg object-contain" />
    @else
        <div class="grid place-items-center h-8 w-8 rounded-lg bg-primary text-primary-foreground font-bold">{{ mb_substr($brand->app_name, 0, 1) }}</div>
    @endif
    <span class="font-semibold tracking-tight truncate">{{ $brand->app_name }}</span>
</div>

@if ($currentTeam)
    @if ($isClerk)
        {{-- Clerks are locked to the workspace they were assigned to — no switcher, just the name. --}}
        <div class="w-full px-4 py-3 border-b text-left">
            <div class="text-[10px] uppercase tracking-wider text-muted-foreground">Workspace</div>
            <div class="mt-0.5 text-sm font-medium truncate">{{ $workspaceLabel }}</div>
        </div>
    @else
        @php
            $allTeams = $user->ownedTeams->merge($user->teams)->unique('id');
        @endphp
        <x-ui.dropdown-menu align="start" width="w-56">
            <x-slot:trigger>
                <button class="w-full px-4 py-3 border-b text-left hover:bg-accent/40 transition-colors">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="text-[10px] uppercase tracking-wider text-muted-foreground">Workspace</div>
                            <div class="mt-0.5 text-sm font-medium truncate">{{ $workspaceLabel }}</div>
                        </div>
                        <svg class="h-3 w-3 text-muted-foreground shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                    </div>
                </button>
            </x-slot:trigger>
            @if ($allTeams->count() > 1)
                <div class="px-2 py-1.5 text-[10px] uppercase tracking-wider text-muted-foreground">Switch workspace</div>
                @foreach ($allTeams as $t)
                    <form method="POST" action="{{ route('workspace.switch', $t) }}">
                        @csrf
                        <x-ui.dropdown-menu-item as="button" type="submit">
                            <span class="flex-1 truncate">{{ $t->name }}</span>
                            @if ($t->id === $currentTeam->id)
                                <svg class="h-3.5 w-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </x-ui.dropdown-menu-item>
                    </form>
                @endforeach
                <x-ui.dropdown-menu-separator />
            @endif
        </x-ui.dropdown-menu>
    @endif
@endif

{{-- Role badge --}}
<div class="px-4 py-2 border-b flex items-center gap-2">
    @php
        $roleLabel = ucwords(str_replace('_', ' ', $user->role ?? 'user'));
        $roleColor = match($user->role) {
            'super_admin' => 'bg-purple-100 text-purple-800',
            'admin'       => 'bg-blue-100 text-blue-800',
            'manager'     => 'bg-green-100 text-green-800',
            default       => 'bg-gray-100 text-gray-700',
        };
    @endphp
    <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-medium {{ $roleColor }}">{{ $roleLabel }}</span>
    <span class="text-xs text-muted-foreground truncate">{{ $user->name }}</span>
</div>

<nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
    @foreach ($nav as $section)
        <div>
            @if (!empty($section['label']))
                <div class="px-2 mb-1 text-[10px] uppercase tracking-wider text-muted-foreground">
                    {{ $section['label'] }}
                </div>
            @endif
            <ul class="space-y-0.5">
                @foreach ($section['items'] as $item)
                    @php
                        $href   = R::has($item['route']) ? route($item['route']) : '#';
                        $active = $isActiveRoute($item['route']);
                    @endphp
                    <li>
                        <a href="{{ $href }}"
                           @class([
                               'group flex items-center gap-3 rounded-md px-2 py-2 text-sm font-medium transition-colors',
                               'bg-accent text-accent-foreground' => $active,
                               'text-muted-foreground hover:bg-accent/60 hover:text-foreground' => !$active,
                           ])>
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>

<div class="border-t p-3 text-xs text-muted-foreground">
    <span class="opacity-70">&copy; {{ date('Y') }} {{ $brand->app_name }}</span>
</div>
