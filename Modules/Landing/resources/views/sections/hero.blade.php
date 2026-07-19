{{--
    Hero Section
    --------------------------------------------------------------------------
    Full-viewport opening section for the landing page.

    Background is built from FOUR stacked layers (outer overflow-hidden keeps
    everything inside the viewport — fixes "elements spilling off edges"):
      1. Base radial gradient (deep indigo → near-black)
      2. Dot-grid overlay (subtle depth, Vercel-style)
      3. Ambient colour blobs (animated drift, using .ambient-blob from layout)
      4. Top-center radial spotlight (draws the eye to the headline)

    Content sits in a max-w-4xl container with px-6, so nothing ever escapes
    the viewport edge regardless of screen size.

    Animations: the [data-reveal] + .is-visible system from the layout handles
    entrance. @keyframes below drive only the ambient blob drift.
--}}
<section
    id="hero"
    aria-label="Hero"
    class="relative flex min-h-screen items-center justify-center overflow-hidden"
>

    {{-- ════════ BACKGROUND LAYERS (all absolutely positioned, non-interactive) ════════ --}}

    {{-- Layer 1: base vertical + radial gradient --}}
    <div
        class="absolute inset-0 -z-30 bg-[#0a0a0f]"
        style="background:
            radial-gradient(ellipse 90% 60% at 50% 0%, rgba(99,102,241,0.18), transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 20%, rgba(139,92,246,0.12), transparent 55%),
            linear-gradient(180deg, #0a0a0f 0%, #0a0a0f 100%);"
        aria-hidden="true"
    ></div>

    {{-- Layer 2: subtle dot-grid (Vercel-style depth) --}}
    <div
        class="absolute inset-0 -z-20 opacity-[0.4]"
        style="background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
               background-size: 32px 32px;
               -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 30%, transparent 75%);
                       mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 30%, transparent 75%);"
        aria-hidden="true"
    ></div>

    {{-- Layer 3: ambient colour blobs (animated drift via keyframes below) --}}
    <div class="absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="ambient-blob hero-blob-1" style="width: 520px; height: 520px; top: -120px; left: -80px; background: radial-gradient(circle, rgba(99,102,241,0.35), transparent 70%);"></div>
        <div class="ambient-blob hero-blob-2" style="width: 460px; height: 460px; top: 60px; right: -60px; background: radial-gradient(circle, rgba(139,92,246,0.30), transparent 70%);"></div>
        <div class="ambient-blob hero-blob-3" style="width: 380px; height: 380px; bottom: -100px; left: 40%; background: radial-gradient(circle, rgba(6,182,212,0.18), transparent 70%);"></div>
    </div>

    {{-- Layer 4: top-center spotlight glow on the headline area --}}
    <div
        class="absolute left-1/2 top-0 -z-10 h-[420px] w-[820px] max-w-[95vw] -translate-x-1/2 rounded-full"
        style="background: radial-gradient(ellipse at center, rgba(99,102,241,0.25), transparent 70%); filter: blur(60px);"
        aria-hidden="true"
    ></div>

    {{-- ════════ CONTENT ════════ --}}
    <div class="relative z-10 mx-auto flex max-w-4xl flex-col items-center px-6 pt-28 pb-20 text-center sm:pt-32">

        {{-- Eyebrow / announcement pill --}}
        <div
            data-reveal
            class="glass mb-8 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-medium text-slate-300"
        >
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-indigo-500"></span>
            </span>
            Now in private beta — join the first 500 members
        </div>

        {{-- Headline --}}
        <h1
            data-reveal
            data-reveal-delay="1"
            class="type-display max-w-3xl text-5xl font-extrabold text-white sm:text-6xl lg:text-7xl"
        >
            Organize, share &amp; discover
            <span class="text-gradient glow-text-primary">GIFs</span>
            like never before
        </h1>

        {{-- Subtitle --}}
        <p
            data-reveal
            data-reveal-delay="2"
            class="type-body mt-6 max-w-2xl text-lg text-slate-400 sm:text-xl"
        >
            The fastest way to upload, tag, and share your GIF collection.
            Smart search, instant categories, and one-click sharing — all in
            one beautifully simple workspace.
        </p>

        {{-- CTA buttons --}}
        <div
            data-reveal
            data-reveal-delay="3"
            class="mt-10 flex w-full flex-col items-center justify-center gap-4 sm:w-auto sm:flex-row"
        >
            <a
                href="{{ route('register') }}"
                {{-- Magnetic button: follows cursor slightly (Alpine.js @mousemove) --}}
                x-data="{ mx: 0, my: 0, magnetic(e) { const r = this.$el.getBoundingClientRect(); const x = e.clientX - (r.left + r.width / 2); const y = e.clientY - (r.top + r.height / 2); this.mx = x * 0.25; this.my = y * 0.25; }, reset() { this.mx = 0; this.my = 0; } }"
                @mousemove="magnetic($event)"
                @mouseleave="reset()"
                :style="`transform: translate(${mx}px, ${my}px)`"
                class="group relative inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-500 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-indigo-500/40 transition-[transform,box-shadow] duration-200 ease-out will-change-transform hover:shadow-indigo-500/60 sm:w-auto"
            >
                Start free — no credit card
                <span class="inline-block transition-transform duration-200 group-hover:translate-x-1">→</span>
            </a>
            <a
                href="#features"
                class="glass inline-flex w-full items-center justify-center gap-2 rounded-xl px-8 py-4 text-base font-semibold text-slate-200 transition-all duration-200 hover:bg-white/10 hover:text-white sm:w-auto"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <polygon points="10 8 16 12 10 16 10 8"/>
                </svg>
                See how it works
            </a>
        </div>

        {{-- Trust row --}}
        <div
            data-reveal
            data-reveal-delay="4"
            class="mt-14 flex flex-col items-center gap-3 sm:flex-row sm:gap-6"
        >
            <span class="text-xs font-medium uppercase tracking-wider text-slate-500">Trusted by teams at</span>
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-sm font-semibold text-slate-600">
                <span>Acme Studio</span>
                <span class="text-slate-700">•</span>
                <span>Pixelcraft</span>
                <span class="text-slate-700">•</span>
                <span>MotionLab</span>
                <span class="text-slate-700">•</span>
                <span>Devhouse</span>
            </div>
        </div>

    </div>

    {{-- Scroll-down indicator --}}
    <div
        data-reveal
        data-reveal-delay="5"
        class="absolute bottom-8 left-1/2 -translate-x-1/2"
        aria-hidden="true"
    >
        <div class="flex h-9 w-6 items-start justify-center rounded-full border border-white/20 p-1.5">
            <span class="h-2 w-1 animate-bounce rounded-full bg-slate-400"></span>
        </div>
    </div>

</section>

{{-- ════════ Scoped keyframe animations for ambient blobs (drift) ════════ --}}
<style>
    @keyframes hero-drift-1 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33%      { transform: translate(40px, 30px) scale(1.08); }
        66%      { transform: translate(-20px, 50px) scale(0.96); }
    }
    @keyframes hero-drift-2 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50%      { transform: translate(-50px, 40px) scale(1.1); }
    }
    @keyframes hero-drift-3 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50%      { transform: translate(30px, -40px) scale(1.05); }
    }
    .hero-blob-1 { animation: hero-drift-1 18s ease-in-out infinite; }
    .hero-blob-2 { animation: hero-drift-2 22s ease-in-out infinite; }
    .hero-blob-3 { animation: hero-drift-3 16s ease-in-out infinite; }

    @media (prefers-reduced-motion: reduce) {
        .hero-blob-1, .hero-blob-2, .hero-blob-3 { animation: none; }
    }
</style>
