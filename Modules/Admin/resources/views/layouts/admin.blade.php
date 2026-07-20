<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} | Admin</title>

    <link rel="icon" href="{{ asset('admin-assets/images/favicon.svg') }}" type="image/svg+xml">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Gentelella v4 CSS (vanilla ES-module build, no jQuery, no Alpine) --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/main-v4-DDS6x4g-.css') }}">

    {{-- Theme initialiser: reads localStorage before first paint to avoid flash --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                var d = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = t || (d ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>

    {{-- Gentelella v4 JS modules (vanilla JS only — sidebar, theme, modals, command palette) --}}
    {{-- NOTE: sidebar-toggle, theme-toggle and modal logic are fully owned by these scripts.  --}}
    {{--       Do NOT add Alpine.js or jQuery equivalents here; they would create conflicts.   --}}
    <script type="module" src="{{ asset('admin-assets/js/rolldown-runtime-DEgBLETi.js') }}"></script>
    <script type="module" src="{{ asset('admin-assets/js/toast-DgCSlJPv.js') }}"></script>
    <script type="module" src="{{ asset('admin-assets/js/menus-BVcs0GJR.js') }}"></script>
    <script type="module" src="{{ asset('admin-assets/js/modal-MTuCfURV.js') }}"></script>
    <script type="module" src="{{ asset('admin-assets/js/main-v4-BFwmMcfm.js') }}"></script>

    {{-- Livewire styles (injected before closing </head>) --}}
    @livewireStyles

    {{-- Mobile touch-target overrides: Gentelella's tb-btn (32px) and
         sidebar-toggle (34px) are below the 44×44 px WCAG 2.5.5 guideline.
         We enlarge the interactive hit area on touch devices only, so the
         visual size stays unchanged on desktop (pointer: fine). --}}
    <style>
        @media (pointer: coarse) {
            /* Topbar icon buttons: grow hit area to 44 px without changing
               the visible icon size. */
            .tb-btn,
            .sidebar-toggle {
                min-width: 44px;
                min-height: 44px;
            }

            /* Avatar button in topbar */
            .tb-avatar {
                min-width: 44px;
                min-height: 44px;
            }
        }
    </style>

    {{-- Per-page styles slot (inject via @push / named slot from Livewire components) --}}
    {{ $styles ?? '' }}
</head>
<body data-shell="admin" data-page="{{ $page ?? 'dashboard' }}" data-breadcrumb="{{ $breadcrumb ?? 'Home' }}">

<a class="skip-link" href="#main-content">Skip to main content</a>

{{-- ═══════════════════════════════════════════════════ SIDEBAR ══ --}}
<aside class="sidebar" aria-label="Primary navigation">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <img src="{{ asset('admin-assets/images/logo-icon.svg') }}" alt="{{ config('app.name') }}" width="24" height="24">
        </div>
        <div class="brand-name">{{ config('app.name') }}</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group">
            <div class="nav-label">General</div>

            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="4" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="10" width="7" height="11" rx="1.5"/>
                </svg>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        {{-- Additional nav items injected by other modules via the $sidebar slot --}}
        {{ $sidebar ?? '' }}
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">
                {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}
                <span class="online"></span>
            </div>
            <div class="sidebar-user-info">
                <div class="name">{{ auth()->user()->name ?? 'Guest' }}</div>
                <div class="role">Admin</div>
            </div>
            <button class="more-btn" aria-label="More options">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                    <circle cx="8" cy="3" r="1.2"/>
                    <circle cx="8" cy="8" r="1.2"/>
                    <circle cx="8" cy="13" r="1.2"/>
                </svg>
            </button>
        </div>
    </div>
</aside>

{{-- ═══════════════════════════════════════════════════ TOPBAR ══ --}}
<header class="topbar">
    <div class="topbar-left">
        {{-- sidebar-toggle is wired by Gentelella main-v4.js (rail mode on desktop, drawer on mobile) --}}
        <button class="sidebar-toggle" type="button"
                aria-label="Open menu" aria-controls="sidebar" aria-expanded="false">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <span class="current" aria-current="page">{{ $breadcrumb ?? 'Home' }}</span>
        </nav>
    </div>

    {{-- Search box opens the command palette (⌘K) — handled by Gentelella main-v4.js --}}
    <div class="search-box">
        <svg class="s-icon" width="14" height="14" viewBox="0 0 16 16" fill="none"
             stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <circle cx="7" cy="7" r="5"/>
            <path d="M11 11l3.5 3.5"/>
        </svg>
        <input type="text" placeholder="Search pages or run a command…"
               aria-label="Open command palette" readonly>
        <kbd>⌘K</kbd>
    </div>

    <div class="topbar-right">
        {{-- theme-toggle is wired by Gentelella main-v4.js --}}
        <button class="tb-btn theme-toggle" type="button"
                title="Toggle theme" aria-label="Toggle theme" aria-pressed="false">
            <svg class="theme-icon-light" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
            </svg>
            <svg class="theme-icon-dark" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>

        @auth
        {{--
            Logout: uses a hidden form + fetch to bypass Gentelella's global
            document.addEventListener("submit") interceptor in main-v4.js,
            which calls preventDefault() on every form submit (intended for
            settings forms) and prevents real navigation from happening.

            We submit programmatically via fetch POST, then redirect on success,
            so the global listener never sees a native submit event.
        --}}
        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none">
            @csrf
        </form>
        <button
            type="button"
            class="tb-btn"
            title="Logout"
            aria-label="Logout"
            onclick="
                fetch('{{ route('logout') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => { window.location.href = '{{ route('login') }}'; })
                  .catch(() => { document.getElementById('logout-form').submit(); });
            "
        >
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
        </button>
        <button class="tb-avatar" type="button" aria-label="Account menu">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </button>
        @endauth
    </div>
</header>

{{-- ══════════════════════════════════════════════════ MAIN ══ --}}
{{-- ─────────────────────────────────────────────────────────── --}}
{{-- LIVEWIRE INTEGRATION NOTES                                  --}}
{{-- • The shell (sidebar, topbar, footer) is static Blade HTML. --}}
{{-- • Dynamic, data-driven sections should be Livewire          --}}
{{--   full-page components using #[Layout('admin::layouts.admin')] --}}
{{-- • Currently static (future Livewire candidates):           --}}
{{--   - sidebar nav items (module-driven menu)                  --}}
{{--   - notification badge counts                               --}}
{{--   - user avatar / name (already reads auth()->user())       --}}
{{-- • Gentelella JS owns: sidebar toggle, theme, modals,        --}}
{{--   command palette, ECharts, DataTables. Do NOT duplicate    --}}
{{--   these with Livewire polls or Alpine.js directives.        --}}
{{-- ─────────────────────────────────────────────────────────── --}}
<main id="main-content" tabindex="-1" class="main">
    <div class="page-wrapper">
        {{ $slot }}
    </div>

    <footer class="footer">
        <span>{{ config('app.name') }} &mdash; Admin Panel</span>
        <span>&copy; {{ date('Y') }}</span>
    </footer>
</main>

{{-- Livewire scripts --}}
@livewireScripts

{{-- Per-page scripts slot --}}
{{ $scripts ?? '' }}

</body>
</html>
