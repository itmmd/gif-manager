{{--
    Navbar Component
    --------------------------------------------------------------------------
    Fixed glassmorphism navbar that becomes opaque on scroll (Linear-style).
    Uses Alpine $store('scroll') defined globally in the landing layout to
    detect scroll position and swap background/border opacity.

    Auth state is resolved server-side via auth() helper. The page performs
    a full reload after login/logout (navigate: false), so the navbar always
    reflects the true session state without any client-side polling.

    Structure:
      - Desktop (≥lg): logo | center nav | auth area (dropdown or guest CTAs)
      - Mobile (<lg):  logo | hamburger → full-screen overlay panel
--}}
@props([
    'links' => [
        ['href' => '#features',    'label' => 'Features'],
        ['href' => '#why-us',      'label' => 'Why Us'],
        ['href' => '#showcase',    'label' => 'Showcase'],
        ['href' => '#statistics',  'label' => 'Stats'],
        ['href' => '#faq',         'label' => 'FAQ'],
    ],
])

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('navbar', () => ({
        mobileOpen: false,
        _scrollY: 0,

        openMenu() {
            this._scrollY = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top      = '-' + this._scrollY + 'px';
            document.body.style.width    = '100%';
            this.mobileOpen = true;
            this.$nextTick(() => {
                const first = this.$refs.panel.querySelector('a, button');
                if (first) first.focus();
            });
        },

        closeMenu() {
            this.mobileOpen = false;
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.top      = '';
            document.body.style.width    = '';
            window.scrollTo({ top: this._scrollY, behavior: 'instant' });
            this.$nextTick(() => this.$refs.hamburger.focus());
        },

        toggleMenu() {
            this.mobileOpen ? this.closeMenu() : this.openMenu();
        },

        handleKeydown(e) {
            if (!this.mobileOpen) return;
            if (e.key === 'Escape') { e.preventDefault(); this.closeMenu(); return; }
            if (e.key === 'Tab') {
                const panel    = this.$refs.panel;
                const selector = 'a[href], button, [tabindex]:not([tabindex="-1"])';
                const focusable = Array.from(panel.querySelectorAll(selector)).filter(el => !el.disabled);
                if (!focusable.length) return;
                const first = focusable[0], last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        },
    }));
});
</script>

<header
    x-data="navbar"
    x-cloak
    @keydown.window="handleKeydown($event)"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    :class="$store.scroll.y > 24
        ? 'bg-[#0a0a0f]/80 backdrop-blur-xl border-b border-white/5 shadow-lg shadow-black/20'
        : 'bg-transparent border-b border-transparent'"
    role="banner"
>
    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 lg:px-8">

        {{-- ── Logo ──────────────────────────────────────────── --}}
        <a href="{{ route('landing') }}" class="group flex items-center gap-2.5 shrink-0">
            <span class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-500 shadow-lg shadow-indigo-500/30 transition-transform duration-300 group-hover:scale-110">
                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polygon points="6 4 20 12 6 20 6 4"/>
                </svg>
            </span>
            <span class="text-lg font-bold tracking-tight text-white">
                GIF<span class="text-gradient">Manager</span>
            </span>
        </a>

        {{-- ── Desktop center nav ────────────────────────────── --}}
        <div class="hidden lg:flex items-center gap-1">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    class="relative rounded-lg px-4 py-2 text-sm font-medium text-slate-300 transition-colors duration-200 hover:text-white"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a
                href="{{ route('gifs.index') }}"
                class="relative rounded-lg px-4 py-2 text-sm font-medium text-slate-300 transition-colors duration-200 hover:text-white"
            >
                Gallery
            </a>
        </div>

        {{-- ── Desktop auth area ─────────────────────────────── --}}
        <div class="hidden lg:flex items-center gap-3 shrink-0">
            @auth
                {{-- Logged-in: user dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button
                        type="button"
                        @click="open = !open"
                        @keydown.escape.window="open = false"
                        class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 ring-1 ring-white/10 transition-all duration-200 hover:bg-white/5 hover:text-white"
                        :aria-expanded="open"
                        aria-haspopup="true"
                    >
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-xs font-bold text-white select-none">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown panel --}}
                    <div
                        x-show="open"
                        x-cloak
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                        class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl border border-white/10 bg-[#111118] py-1 shadow-xl shadow-black/30"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        @if(auth()->user()->isAdmin())
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-200 transition-colors hover:bg-white/5 hover:text-white"
                                role="menuitem"
                            >
                                <svg class="h-4 w-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                                </svg>
                                Admin Panel
                            </a>
                            <div class="my-1 border-t border-white/10" role="separator"></div>
                        @endif

                        <a
                            href="{{ route('profile') }}"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 transition-colors hover:bg-white/5 hover:text-white"
                            role="menuitem"
                        >
                            <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>

                        <div class="my-1 border-t border-white/10" role="separator"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 transition-colors hover:bg-white/5 hover:text-rose-400"
                                role="menuitem"
                            >
                                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Guest: Sign in / Get Started --}}
                <a
                    href="{{ route('login') }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-slate-300 transition-colors duration-200 hover:text-white"
                >
                    Sign in
                </a>
                <a
                    href="{{ route('register') }}"
                    class="group relative rounded-lg bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:shadow-indigo-500/50 hover:-translate-y-0.5"
                >
                    Get Started
                    <span class="ml-1 inline-block transition-transform duration-200 group-hover:translate-x-0.5">→</span>
                </a>
            @endauth
        </div>

        {{-- ── Mobile hamburger — min 44×44 px (WCAG 2.5.5) ─── --}}
        <button
            x-ref="hamburger"
            type="button"
            @click="toggleMenu()"
            class="lg:hidden inline-flex h-11 w-11 items-center justify-center rounded-lg text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
            :aria-expanded="mobileOpen"
            aria-label="Toggle navigation menu"
        >
            <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </nav>

    {{-- ── Mobile full-screen overlay ──────────────────────────────────── --}}
    <div
        x-ref="panel"
        id="mobile-nav-panel"
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="nav-mobile-panel lg:hidden"
        role="dialog"
        aria-modal="true"
        aria-label="Navigation menu"
    >
        <div class="flex flex-col gap-1 px-6 py-6">

            {{-- Nav links --}}
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    @click="closeMenu()"
                    class="flex min-h-[44px] items-center rounded-lg px-3 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a
                href="{{ route('gifs.index') }}"
                @click="closeMenu()"
                class="flex min-h-[44px] items-center rounded-lg px-3 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
            >
                Gallery
            </a>

            {{-- Auth section --}}
            <div class="flex flex-col gap-2 pt-4 mt-4 border-t border-white/10">

                @auth
                    {{-- User info header --}}
                    <div class="flex items-center gap-3 px-3 py-2 mb-1">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-sm font-bold text-white select-none shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.dashboard') }}"
                            @click="closeMenu()"
                            class="flex min-h-[44px] items-center gap-2.5 rounded-lg px-3 text-sm font-medium text-indigo-300 ring-1 ring-indigo-500/30 hover:bg-indigo-500/10 transition-colors"
                        >
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                            </svg>
                            Admin Panel
                        </a>
                    @endif

                    <a
                        href="{{ route('profile') }}"
                        @click="closeMenu()"
                        class="flex min-h-[44px] items-center gap-2.5 rounded-lg px-3 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
                    >
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="flex w-full min-h-[44px] items-center gap-2.5 rounded-lg px-3 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-rose-400 transition-colors"
                        >
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign out
                        </button>
                    </form>

                @else
                    <a
                        href="{{ route('login') }}"
                        @click="closeMenu()"
                        class="flex min-h-[44px] items-center justify-center rounded-lg px-4 text-sm font-medium text-slate-200 ring-1 ring-white/10 hover:bg-white/5 transition-colors"
                    >
                        Sign in
                    </a>
                    <a
                        href="{{ route('register') }}"
                        @click="closeMenu()"
                        class="flex min-h-[44px] items-center justify-center rounded-lg bg-gradient-to-r from-indigo-500 to-violet-500 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30"
                    >
                        Get Started
                    </a>
                @endauth

            </div>
        </div>
    </div>
</header>
