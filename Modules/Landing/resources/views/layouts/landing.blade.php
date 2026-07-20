<!DOCTYPE html>
<html lang="en" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO: Primary --}}
    <title>{{ $title ?? 'GIF Manager — Organize, Share & Discover GIFs' }}</title>
    <meta name="description" content="{{ $description ?? 'The fastest way to upload, organize, and share your GIF collection. Smart search, instant categories, one-click sharing.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'gif manager, gif organizer, upload gif, share gif, gif collection' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- SEO: Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'GIF Manager — Organize, Share & Discover GIFs' }}">
    <meta property="og:description" content="{{ $description ?? 'The fastest way to upload, organize, and share your GIF collection.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- SEO: Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'GIF Manager — Organize, Share & Discover GIFs' }}">
    <meta name="twitter:description" content="{{ $description ?? 'The fastest way to upload, organize, and share your GIF collection.' }}">
    <meta name="twitter:image" content="{{ asset('images/og-image.png') }}">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    {{--
        Fonts: Inter is self-hosted in public/fonts/inter/.
        @font-face is declared in this layout's <style> block (not in app.css)
        to prevent Vite's minifier from removing the space between url() and
        format() — which causes browsers to silently ignore the font.
        --font-sans is overridden in app.css @theme to wire Inter into Tailwind.
    --}}

    {{-- Tailwind CSS + Alpine.js (main app build) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Landing-specific CSS custom properties & dark theme default --}}
    <style>
        /*
         * Inter Variable Font — self-hosted, no CDN dependency.
         *
         * Declared here (in a <style> tag) rather than in app.css to avoid
         * Vite's CSS minifier removing the required space between url() and
         * format() — a known quirk in Tailwind v4's build pipeline that causes
         * browsers to silently reject the @font-face src declaration.
         *
         * font-display: swap  → text stays visible during font load (no FOIT).
         * font-weight: 100 900 → single variable-font file for all weights.
         */
        @font-face {
            font-family: 'Inter';
            font-style:  normal;
            font-weight: 100 900;
            font-display: swap;
            src: url('/fonts/inter/inter-latin-variable.woff2') format('woff2-variations');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC,
                           U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329,
                           U+2000-206F, U+2074, U+20AC, U+2122, U+2191,
                           U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        :root {
            /* Brand gradient palette */
            --landing-primary:   #6366f1;   /* indigo-500  */
            --landing-secondary: #8b5cf6;   /* violet-500  */
            --landing-accent:    #06b6d4;   /* cyan-500    */
            --landing-glow:      rgba(99, 102, 241, 0.35);

            /* Surface colours (dark theme is default) */
            --landing-bg:        #0a0a0f;
            --landing-surface:   #111118;
            --landing-surface-2: #1a1a24;
            --landing-border:    rgba(255,255,255,0.08);
            --landing-text:      #f1f5f9;
            --landing-text-muted:#94a3b8;

            /* Typography */
            --font-landing: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        * { font-family: var(--font-landing); }

        body {
            background-color: var(--landing-bg);
            color: var(--landing-text);
            overflow-x: hidden;
            /* Inter renders crisper with antialiasing */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /*
         * Type scale — applied globally for landing sections.
         *
         * Large headings (Hero h1, section titles) benefit from:
         *   - negative letter-spacing: tightens the optically-loose gap
         *     between characters at large sizes (Inter's default spacing
         *     is tuned for body text, not 56–80px display use)
         *   - tight line-height: prevents too much vertical air in
         *     multi-line headlines
         *
         * These are defined as CSS custom properties so individual
         * sections can reference them without scattering magic numbers.
         */
        :root {
            --type-display-tracking: -0.03em;   /* hero h1, large titles  */
            --type-heading-tracking: -0.02em;   /* section h2 headings    */
            --type-display-leading:  1.05;      /* hero h1 line-height    */
            --type-heading-leading:  1.15;      /* section h2 line-height */
            --type-body-leading:     1.6;       /* paragraphs             */
        }

        /* Display heading — Hero h1 */
        .type-display {
            letter-spacing: var(--type-display-tracking);
            line-height:    var(--type-display-leading);
            font-variation-settings: 'wght' 800;   /* variable font axis */
        }

        /* Section headings */
        .type-heading {
            letter-spacing: var(--type-heading-tracking);
            line-height:    var(--type-heading-leading);
            font-variation-settings: 'wght' 700;
        }

        /* Body / paragraph text */
        .type-body {
            line-height: var(--type-body-leading);
        }

        /* ── Scroll-triggered base state ── */
        [data-reveal] {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        [data-reveal].is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        [data-reveal-delay="1"] { transition-delay: 0.1s; }
        [data-reveal-delay="2"] { transition-delay: 0.2s; }
        [data-reveal-delay="3"] { transition-delay: 0.3s; }
        [data-reveal-delay="4"] { transition-delay: 0.4s; }
        [data-reveal-delay="5"] { transition-delay: 0.5s; }
        [data-reveal-delay="6"] { transition-delay: 0.6s; }

        /* ── Gradient text utility ── */
        .text-gradient {
            background: linear-gradient(135deg, var(--landing-primary), var(--landing-secondary), var(--landing-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Glassmorphism utility ── */
        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--landing-border);
        }

        /* ── Mobile nav panel — intentionally solid, never glass ── */
        /*
         * Full-screen overlay that covers the entire viewport on every device:
         *
         * • 100dvh   — dynamic viewport height: shrinks/grows with the browser
         *              chrome (address bar) on iOS Safari & Chrome mobile. This
         *              prevents the panel being taller than the visible area.
         * • 100vh    — fallback for browsers that don't support dvh yet
         *              (Chrome < 108, Safari < 15.4, Firefox < 101).
         * • safe-area padding — keeps content away from notch (top) and the
         *              home-indicator bar (bottom) on notch / Dynamic Island
         *              iPhones and similar Android devices.
         *              Requires viewport-fit=cover in the <meta> tag (set above).
         * • overflow-y: auto — makes the link list scrollable when the viewport
         *              is short (e.g. landscape on iPhone SE / 8).
         *
         * The panel is position:fixed so it sits above the page in the stacking
         * context and is unaffected by the page scroll position.
         */
        .nav-mobile-panel {
            position: fixed;
            inset: 0;
            z-index: 40; /* below the navbar header (z-50) so the topbar stays visible */
            background: #0d0d15;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;

            /* dvh with vh fallback */
            height: 100vh;
            height: 100dvh;

            /* Safe-area insets for notch / Dynamic Island / home-indicator */
            padding-top: calc(4rem + env(safe-area-inset-top));    /* 4rem = navbar height */
            padding-bottom: env(safe-area-inset-bottom, 1rem);
            padding-left: env(safe-area-inset-left, 0px);
            padding-right: env(safe-area-inset-right, 0px);
        }

        /* Reduce backdrop-filter cost on low-end / small-screen devices.
           Devices that hint at reduced motion typically have lower GPU budgets
           too; we keep the border/bg tint so the card still reads as "glass". */
        @media (prefers-reduced-motion: reduce) {
            .glass {
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
                background: rgba(255,255,255,0.07);
            }
        }

        /* On very small viewports the blur compositor layer can cause scroll
           jank. Disable blur below 640 px (sm breakpoint) where GPU is
           most constrained, while keeping the semi-transparent surface. */
        @media (max-width: 639px) {
            .glass {
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
                background: rgba(255,255,255,0.07);
            }

            /* Navbar also uses Tailwind backdrop-blur-xl via :class binding.
               Override it on mobile to avoid compositor jank. */
            header[x-data] {
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
        }

        /* ── Glow utilities ── */
        .glow-primary {
            box-shadow: 0 0 40px var(--landing-glow);
        }
        .glow-text-primary {
            text-shadow: 0 0 40px rgba(99, 102, 241, 0.6);
        }

        /* ── Ambient blobs ── */
        .ambient-blob {
            position: absolute;
            border-radius: 9999px;
            filter: blur(80px);
            pointer-events: none;
            will-change: transform;
        }
    </style>

    @livewireStyles

    {{ $styles ?? '' }}

    {{-- Alpine.js global scroll store — initialised before components mount --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('scroll', {
                y: 0,
                direction: 'down',
                _last: 0,
                init() {
                    window.addEventListener('scroll', () => {
                        const current = window.scrollY;
                        this.direction = current > this._last ? 'down' : 'up';
                        this._last = current;
                        this.y = current;
                    }, { passive: true });
                }
            });
        });
    </script>

    {{-- Intersection Observer: activates [data-reveal] elements on scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            document.querySelectorAll('[data-reveal]').forEach(el => io.observe(el));

            // Re-run for elements added later (Livewire lazy loads, etc.)
            const mo = new MutationObserver(() => {
                document.querySelectorAll('[data-reveal]:not(.is-visible)').forEach(el => io.observe(el));
            });
            mo.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</head>

<body x-data x-cloak>

    {{-- ─────────────────────────────────────────────────────── --}}
    {{-- Slot: full page content injected by Livewire component  --}}
    {{-- ─────────────────────────────────────────────────────── --}}
    {{ $slot }}

    @livewireScripts

    {{ $scripts ?? '' }}

</body>
</html>
