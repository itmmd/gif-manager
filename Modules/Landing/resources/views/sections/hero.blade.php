@php $hero = config('landing.hero'); @endphp

<section
    id="hero"
    aria-label="Hero"
    class="relative flex min-h-screen items-center justify-center overflow-hidden"
>

    {{-- Background layers (all absolutely positioned, non-interactive) --}}

    {{-- Layer 1: base vertical + radial gradient --}}
    <div
        class="absolute inset-0 -z-30 bg-[#0a0a0f]"
        style="background:
            radial-gradient(ellipse 90% 60% at 50% 0%, rgba(99,102,241,0.18), transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 20%, rgba(139,92,246,0.12), transparent 55%),
            linear-gradient(180deg, #0a0a0f 0%, #0a0a0f 100%);"
        aria-hidden="true"
    ></div>

    {{-- Layer 2: subtle dot-grid --}}
    <div
        class="absolute inset-0 -z-20 opacity-[0.4]"
        style="background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
               background-size: 32px 32px;
               -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 30%, transparent 75%);
                       mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, black 30%, transparent 75%);"
        aria-hidden="true"
    ></div>

    {{-- Layer 3: ambient colour blobs (animated drift) --}}
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

    {{-- Content --}}
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
            {{ $hero['announcement'] }}
        </div>

        {{-- Headline --}}
        <h1
            data-reveal
            data-reveal-delay="1"
            class="type-display max-w-3xl text-3xl font-extrabold text-white sm:text-5xl lg:text-5xl"
        >
            {{ $hero['headline'] }}
            <span class="text-gradient glow-text-primary">{{ $hero['headline_em'] }}</span>
            {{ $hero['headline_tail'] }}
        </h1>

        {{-- Subtitle --}}
        <p
            data-reveal
            data-reveal-delay="2"
            class="type-body mt-6 max-w-2xl text-lg text-slate-400 sm:text-xl"
        >
            {{ $hero['subtitle'] }}
        </p>

        {{-- CTA buttons --}}
        <div
            data-reveal
            data-reveal-delay="3"
            class="mt-10 flex w-full flex-col items-center justify-center gap-4 sm:w-auto sm:flex-row"
        >
            <a
                href="{{ route('register') }}"
                {{-- Magnetic button: follows cursor slightly --}}
                x-data="{ mx: 0, my: 0, magnetic(e) { const r = this.$el.getBoundingClientRect(); const x = e.clientX - (r.left + r.width / 2); const y = e.clientY - (r.top + r.height / 2); this.mx = x * 0.25; this.my = y * 0.25; }, reset() { this.mx = 0; this.my = 0; } }"
                @mousemove="magnetic($event)"
                @mouseleave="reset()"
                :style="`transform: translate(${mx}px, ${my}px)`"
                class="group relative inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-500 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-indigo-500/40 transition-[transform,box-shadow] duration-200 ease-out will-change-transform hover:shadow-indigo-500/60 sm:w-auto"
            >
                {{ $hero['cta_primary'] }}
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
                {{ $hero['cta_secondary'] }}
            </a>
        </div>

        {{-- Trust row --}}
        <div
            data-reveal
            data-reveal-delay="4"
            class="mt-14 flex flex-col items-center gap-3 sm:flex-row sm:gap-6"
        >
            <span class="text-xs font-medium uppercase tracking-wider text-slate-500">{{ $hero['trust_label'] }}</span>
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-sm font-semibold text-slate-600">
                @foreach ($hero['trust_logos'] as $i => $logo)
                    <span>{{ $logo }}</span>
                    @if (!$loop->last)
                        <span class="text-slate-700">•</span>
                    @endif
                @endforeach
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

{{-- Scoped keyframe animations for ambient blobs (drift) --}}
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
