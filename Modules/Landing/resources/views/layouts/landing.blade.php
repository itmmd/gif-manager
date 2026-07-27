@php
    $seo       = config('landing.seo');
    $siteTitle = $title ?? $seo['title'];
    $siteDesc  = $description ?? $seo['description'];
    $keywords  = $keywords ?? $seo['keywords'];
    $ogImage   = $seo['og_image'];
@endphp

<!DOCTYPE html>
<html lang="en" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteTitle }}</title>
    <meta name="description" content="{{ $siteDesc }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="robots" content="{{ $seo['robots'] }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="{{ $seo['og_type'] }}">
    <meta property="og:title" content="{{ $siteTitle }}">
    <meta property="og:description" content="{{ $siteDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta property="og:image:width" content="{{ $ogImage['width'] }}">
    <meta property="og:image:height" content="{{ $ogImage['height'] }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $siteTitle }}">
    <meta name="twitter:description" content="{{ $siteDesc }}">
    <meta name="twitter:image" content="{{ asset('images/og-image.png') }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url('/fonts/inter/inter-latin-variable.woff2') format('woff2-variations');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC,
                           U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329,
                           U+2000-206F, U+2074, U+20AC, U+2122, U+2191,
                           U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        :root {
            --landing-primary:   #6366f1;
            --landing-secondary: #8b5cf6;
            --landing-accent:    #06b6d4;
            --landing-glow:      rgba(99, 102, 241, 0.35);

            --landing-bg:        #0a0a0f;
            --landing-surface:   #111118;
            --landing-surface-2: #1a1a24;
            --landing-border:    rgba(255,255,255,0.08);
            --landing-text:      #f1f5f9;
            --landing-text-muted:#94a3b8;

            --font-landing: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;

            --type-display-tracking: -0.03em;
            --type-heading-tracking: -0.02em;
            --type-display-leading:  1.05;
            --type-heading-leading:  1.15;
            --type-body-leading:     1.6;
        }

        * { font-family: var(--font-landing); }

        body {
            background-color: var(--landing-bg);
            color: var(--landing-text);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .type-display {
            letter-spacing: var(--type-display-tracking);
            line-height:    var(--type-display-leading);
            font-variation-settings: 'wght' 800;
        }

        .type-heading {
            letter-spacing: var(--type-heading-tracking);
            line-height:    var(--type-heading-leading);
            font-variation-settings: 'wght' 700;
        }

        .type-body {
            line-height: var(--type-body-leading);
        }

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

        .text-gradient {
            background: linear-gradient(135deg, var(--landing-primary), var(--landing-secondary), var(--landing-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--landing-border);
        }

        .nav-mobile-panel {
            position: fixed;
            inset: 0;
            z-index: 40;
            background: #0d0d15;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            height: 100vh;
            height: 100dvh;
            padding-top: calc(4rem + env(safe-area-inset-top));
            padding-bottom: env(safe-area-inset-bottom, 1rem);
            padding-left: env(safe-area-inset-left, 0px);
            padding-right: env(safe-area-inset-right, 0px);
        }

        @media (prefers-reduced-motion: reduce) {
            .glass {
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
                background: rgba(255,255,255,0.07);
            }
        }

        @media (max-width: 639px) {
            .glass {
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
                background: rgba(255,255,255,0.07);
            }
            header[x-data] {
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }
        }

        .glow-primary      { box-shadow: 0 0 40px var(--landing-glow); }
        .glow-text-primary { text-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }

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

            const mo = new MutationObserver(() => {
                document.querySelectorAll('[data-reveal]:not(.is-visible)').forEach(el => io.observe(el));
            });

            mo.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</head>

<body x-data x-cloak>

    {{ $slot }}

    @livewireScripts

    {{ $scripts ?? '' }}

</body>
</html>
