{{--
    Navbar Component
    --------------------------------------------------------------------------
    Fixed glassmorphism navbar that becomes opaque on scroll (Linear-style).
    Uses Alpine $store('scroll') defined globally in the landing layout to
    detect scroll position and swap background/border opacity.

    Structure:
      - Desktop (≥lg): 3-column grid — logo | center nav | auth CTAs
      - Mobile (<lg):  logo | hamburger → slide-down panel

    Section anchors (#features, #why-us, ...) point to the section ids in
    livewire/landing.blade.php and scroll smoothly thanks to the
    `scroll-smooth` class on the <html> tag in the layout.
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

<header
    x-data="{
        mobileOpen: false,
        _scrollY: 0,

        /*
         * iOS Safari scroll-lock bug:
         * Setting overflow:hidden on <body> alone causes the page to jump to
         * the top when re-opened. The fix: record scrollY, switch body to
         * position:fixed with top:-{scrollY}px, then restore on close.
         */
        openMenu() {
            this._scrollY = window.scrollY;
            document.body.style.overflow   = 'hidden';
            document.body.style.position   = 'fixed';
            document.body.style.top        = `-${this._scrollY}px`;
            document.body.style.width      = '100%';
            this.mobileOpen = true;
            // Move focus to first focusable element in panel after transition
            this.$nextTick(() => {
                const first = this.$refs.panel.querySelector('a, button');
                if (first) first.focus();
            });
        },

        closeMenu() {
            this.mobileOpen = false;
            document.body.style.overflow   = '';
            document.body.style.position   = '';
            document.body.style.top        = '';
            document.body.style.width      = '';
            window.scrollTo({ top: this._scrollY, behavior: 'instant' });
            // Return focus to hamburger button
            this.$nextTick(() => this.$refs.hamburger.focus());
        },

        toggleMenu() { this.mobileOpen ? this.closeMenu() : this.openMenu(); },

        /*
         * Focus trap: when the panel is open, Tab/Shift-Tab should cycle only
         * within the panel. Also handles Escape to close.
         */
        handleKeydown(e) {
            if (!this.mobileOpen) return;

            if (e.key === 'Escape') {
                e.preventDefault();
                this.closeMenu();
                return;
            }

            if (e.key === 'Tab') {
                const panel = this.$refs.panel;
                const focusable = [...panel.querySelectorAll(
                    'a[href], button, [tabindex]:not([tabindex=\"-1\"])'
                )].filter(el => !el.disabled);

                if (!focusable.length) return;

                const first = focusable[0];
                const last  = focusable[focusable.length - 1];

                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
    }"
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
                {{-- Simple "play/GIF" glyph --}}
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
        </div>

        {{-- ── Desktop auth CTAs ─────────────────────────────── --}}
        <div class="hidden lg:flex items-center gap-3 shrink-0">
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
        </div>

        {{-- ── Mobile hamburger ──────────────────────────────── --}}
        {{-- min 44×44 px touch target (WCAG 2.5.5) --}}
        <button
            x-ref="hamburger"
            type="button"
            @click="toggleMenu()"
            class="lg:hidden inline-flex h-11 w-11 items-center justify-center rounded-lg text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
            :aria-expanded="mobileOpen"
            :aria-controls="mobileOpen ? 'mobile-nav-panel' : undefined"
            aria-label="Toggle navigation menu"
        >
            {{-- Hamburger ↔ Close icon swap --}}
            <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </nav>

    {{-- ── Mobile full-screen overlay panel ──────────────── --}}
    {{--
        Rendered as a position:fixed full-viewport overlay (see .nav-mobile-panel
        in landing.blade.php for the layout rules: dvh height, safe-area padding,
        overflow-y:auto for landscape / short viewports).

        The panel sits at z-40, one layer below the header (z-50), so the
        navbar's topbar row (logo + hamburger/close) stays on top and is
        always tappable.

        Focus management:
          - openMenu()  → moves focus to first link inside x-ref="panel"
          - closeMenu() → returns focus to x-ref="hamburger"
          - handleKeydown() → Tab cycles within panel; Escape closes
    --}}
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
            {{-- ── Nav links ─────────────────────────────── --}}
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    @click="closeMenu()"
                    class="flex min-h-[44px] items-center rounded-lg px-3 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach

            {{-- ── Auth CTAs ──────────────────────────────── --}}
            <div class="flex flex-col gap-3 pt-4 mt-4 border-t border-white/10">
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
            </div>
        </div>
    </div>
</header>
