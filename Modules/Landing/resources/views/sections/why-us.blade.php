{{--
    Why Choose Us Section  (Task 25)
    --------------------------------------------------------------------------
    Requirements covered:
      - Layout COMPLETELY different from Features: two-column (text left,
        visual mockup right) instead of a card grid.
      - Stagger Animation: list items reveal one-by-one with increasing
        delay as they enter the viewport.
      - Parallax: the visual mockup and ambient blobs translate at a
        different speed than the text column, driven by the global
        Alpine $store('scroll') (defined in the landing layout).

    No external libs — pure Alpine + CSS transforms.
--}}
<section
    id="why-us"
    aria-label="Why Choose Us"
    class="relative overflow-hidden py-32"
    x-data
>

    {{-- ── Parallax background layer (moves slower than content) ── --}}
    {{-- translateY tied to scroll store; factor 0.12 = gentle parallax --}}
    <div
        class="pointer-events-none absolute inset-0 -z-10"
        :style="`transform: translateY(${$store.scroll.y * 0.12}px)`"
        aria-hidden="true"
    >
        <div class="ambient-blob" style="width: 500px; height: 500px; top: 15%; right: -120px; background: radial-gradient(circle, rgba(139,92,246,0.12), transparent 70%);"></div>
        <div class="ambient-blob" style="width: 380px; height: 380px; bottom: 10%; left: -80px; background: radial-gradient(circle, rgba(6,182,212,0.08), transparent 70%);"></div>
    </div>

    <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-16 px-6 lg:grid-cols-2 lg:px-8">

        {{-- ════════ LEFT COLUMN: copy + benefit list ════════ --}}
        <div>
            <p
                data-reveal
                class="glass mb-4 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wider text-violet-300"
            >
                Why Choose Us
            </p>

            <h2
                data-reveal
                data-reveal-delay="1"
                class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl"
            >
                Built for people who
                <span class="text-gradient">move fast</span>
            </h2>

            <p
                data-reveal
                data-reveal-delay="2"
                class="mt-5 max-w-xl text-lg leading-relaxed text-slate-400"
            >
                Most GIF tools feel like a cluttered file manager. We rebuilt
                the experience from scratch — fast, focused, and beautiful.
            </p>

            {{-- Benefit list with stagger reveal --}}
            <ul class="mt-10 space-y-6">
                @php
                    $benefits = [
                        [
                            'title' => 'Designed for speed',
                            'desc'  => 'Every interaction is optimized — from upload to search, nothing takes more than a click.',
                        ],
                        [
                            'title' => 'Privacy first',
                            'desc'  => 'Your library is yours. No public exposure unless you choose to share.',
                        ],
                        [
                            'title' => 'Works on every device',
                            'desc'  => 'Fully responsive. Manage your GIFs on desktop, tablet, or phone without compromise.',
                        ],
                        [
                            'title' => 'Built by creators, for creators',
                            'desc'  => 'We use GIF Manager every day. What you see is shaped by real workflows.',
                        ],
                    ];
                @endphp

                @foreach ($benefits as $i => $b)
                    <li
                        data-reveal
                        data-reveal-delay="{{ min($i + 1, 6) }}"
                        class="flex items-start gap-4"
                    >
                        {{-- Check badge --}}
                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500/30 to-violet-500/20 ring-1 ring-inset ring-white/10">
                            <svg class="h-4 w-4 text-indigo-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-white">{{ $b['title'] }}</h3>
                            <p class="mt-1 text-sm leading-relaxed text-slate-400">{{ $b['desc'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ════════ RIGHT COLUMN: visual mockup with parallax ════════ --}}
        {{-- Parallax disabled on mobile (<lg): stacked layout + translateY
             causes visible jitter when scrolling on narrow screens. --}}
        <div
            data-reveal
            data-reveal-delay="2"
            class="relative"
            :style="window.innerWidth >= 1024 ? `transform: translateY(${$store.scroll.y * 0.06}px)` : ''"
        >
            <div class="glass relative overflow-hidden rounded-3xl p-2 shadow-2xl shadow-violet-500/10">
                {{-- Faux app window chrome --}}
                <div class="flex items-center gap-1.5 px-4 py-3">
                    <span class="h-3 w-3 rounded-full bg-red-500/60"></span>
                    <span class="h-3 w-3 rounded-full bg-yellow-500/60"></span>
                    <span class="h-3 w-3 rounded-full bg-green-500/60"></span>
                </div>

                {{-- Faux dashboard mockup (pure CSS, no image dependency) --}}
                <div class="space-y-3 rounded-2xl bg-[#0d0d14] p-5">
                    {{-- Search bar --}}
                    <div class="flex items-center gap-2 rounded-lg bg-white/5 px-3 py-2.5">
                        <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <div class="h-3 w-40 rounded bg-gradient-to-r from-slate-700 to-slate-600/50"></div>
                    </div>

                    {{-- GIF grid placeholder --}}
                    <div class="grid grid-cols-3 gap-3">
                        @foreach (['from-indigo-500/30 to-violet-500/10','from-violet-500/30 to-cyan-500/10','from-cyan-500/30 to-indigo-500/10','from-violet-500/30 to-indigo-500/10','from-cyan-500/30 to-violet-500/10','from-indigo-500/30 to-cyan-500/10'] as $g)
                            <div class="aspect-square rounded-lg bg-gradient-to-br {{ $g }} ring-1 ring-inset ring-white/5">
                                <div class="flex h-full items-center justify-center">
                                    <svg class="h-5 w-5 text-white/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Sidebar item row --}}
                    <div class="flex items-center gap-2 rounded-lg bg-white/5 px-3 py-2">
                        <div class="h-6 w-6 rounded-md bg-gradient-to-br from-indigo-500/40 to-violet-500/20"></div>
                        <div class="h-2.5 flex-1 rounded bg-slate-700/60"></div>
                        <div class="h-2.5 w-10 rounded bg-slate-700/40"></div>
                    </div>
                </div>
            </div>

            {{-- Floating accent glow behind mockup --}}
            <div class="pointer-events-none absolute -inset-4 -z-10 rounded-3xl bg-gradient-to-tr from-indigo-500/10 via-violet-500/5 to-transparent blur-2xl" aria-hidden="true"></div>
        </div>

    </div>
</section>
