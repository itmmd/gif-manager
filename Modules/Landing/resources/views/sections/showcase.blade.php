{{--
    Showcase Section  (Task 26)
    --------------------------------------------------------------------------
    Requirements covered:
      - Grid of sample content (placeholder images — no real GIF module yet).
      - Hover interaction: subtle zoom + info overlay.
      - Smooth transition into the next section (Statistics) via a
        bottom gradient fade that blends into the page background.
    --}}
<section
    id="showcase"
    aria-label="Showcase"
    class="relative overflow-hidden py-32"
>

    {{-- Ambient glow --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="ambient-blob" style="width: 420px; height: 420px; top: 20%; left: 50%; margin-left: -210px; background: radial-gradient(circle, rgba(99,102,241,0.10), transparent 70%);"></div>
    </div>

    {{-- ── Section header ── --}}
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">

        <div class="mx-auto max-w-2xl text-center">
            <p
                data-reveal
                class="glass mb-4 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wider text-cyan-300"
            >
                Showcase
            </p>
            <h2
                data-reveal
                data-reveal-delay="1"
                class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl"
            >
                See your library come
                <span class="text-gradient">to life</span>
            </h2>
            <p
                data-reveal
                data-reveal-delay="2"
                class="mt-5 text-lg leading-relaxed text-slate-400"
            >
                A glimpse of how your GIFs are organized, searchable, and
                ready to share — all from one beautiful dashboard.
            </p>
        </div>

        {{-- ── Showcase grid ── --}}
        <div class="mx-auto mt-16 max-w-6xl columns-1 gap-5 sm:columns-2 lg:columns-3">

            @php
                $items = [
                    ['label' => 'Gaming Reactions',  'gradient' => 'from-indigo-600/50 to-violet-700/40', 'h' => 'h-56'],
                    ['label' => 'Team Memes',       'gradient' => 'from-violet-600/50 to-fuchsia-600/40','h' => 'h-44'],
                    ['label' => 'Tech Demos',        'gradient' => 'from-cyan-600/50 to-blue-700/40',    'h' => 'h-64'],
                    ['label' => 'Celebrations',      'gradient' => 'from-pink-600/50 to-rose-600/40',    'h' => 'h-48'],
                    ['label' => 'Nature & Travel',    'gradient' => 'from-emerald-600/50 to-teal-700/40', 'h' => 'h-56'],
                    ['label' => 'UI Animations',     'gradient' => 'from-amber-600/50 to-orange-700/40', 'h' => 'h-52'],
                    ['label' => 'Product Mockups',   'gradient' => 'from-indigo-600/50 to-blue-600/40',  'h' => 'h-44'],
                    ['label' => 'Tutorial Steps',    'gradient' => 'from-violet-600/50 to-purple-700/40','h' => 'h-60'],
                    ['label' => 'Social Media Ready','gradient' => 'from-cyan-600/50 to-sky-700/40',    'h' => 'h-48'],
                ];
            @endphp

            @foreach ($items as $i => $item)
                <div
                    data-reveal
                    data-reveal-delay="{{ ($i % 3) + 1 }}"
                    class="group relative mb-5 break-inside-avoid overflow-hidden rounded-2xl"
                >
                    {{-- Placeholder card (gradient-only, no real images) --}}
                    <div class="{{ $item['h'] }} w-full rounded-2xl bg-gradient-to-br {{ $item['gradient'] }} ring-1 ring-inset ring-white/10 transition-transform duration-500 ease-out group-hover:scale-[1.03]">
                        {{-- Placeholder image icon --}}
                        <div class="flex h-full items-center justify-center">
                            <svg class="h-10 w-10 text-white/15 transition-transform duration-500 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 flex items-end rounded-2xl bg-gradient-to-t from-black/80 via-black/20 to-transparent p-5 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $item['label'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-300">24 GIFs</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- ── Smooth bottom transition into next section ── --}}
    {{-- Gradient fade from section surface → page background --}}
    <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#0a0a0f] to-transparent" aria-hidden="true"></div>

</section>
