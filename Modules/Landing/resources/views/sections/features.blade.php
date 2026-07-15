{{--
    Features Section  (Task 24)
    --------------------------------------------------------------------------
    Requirements covered:
      - Floating glassmorphism cards (backdrop-blur + translucent surface)
      - Scroll reveal per card via existing [data-reveal] system
      - Hover effect: glow + scale/lift
      - 6 GIF-related features
    --}}
<section
    id="features"
    aria-label="Features"
    class="relative overflow-hidden py-32"
>

    {{-- Ambient background: two soft glows for depth --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="ambient-blob" style="width: 480px; height: 480px; top: 10%; left: -100px; background: radial-gradient(circle, rgba(99,102,241,0.10), transparent 70%);"></div>
        <div class="ambient-blob" style="width: 420px; height: 420px; bottom: 5%; right: -80px; background: radial-gradient(circle, rgba(6,182,212,0.08), transparent 70%);"></div>
    </div>

    {{-- ── Section header ── --}}
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">

        <div class="mx-auto max-w-2xl text-center">
            <p
                data-reveal
                class="glass mb-4 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wider text-indigo-300"
            >
                Features
            </p>
            <h2
                data-reveal
                data-reveal-delay="1"
                class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl"
            >
                Everything you need to
                <span class="text-gradient">manage your GIFs</span>
            </h2>
            <p
                data-reveal
                data-reveal-delay="2"
                class="mt-5 text-lg leading-relaxed text-slate-400"
            >
                Powerful tools wrapped in a simple, beautiful interface —
                built for creators, teams, and anyone who lives in motion.
            </p>
        </div>

        {{-- ── Cards grid ── --}}
        <div class="mx-auto mt-16 grid max-w-6xl gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @php
                $features = [
                    [
                        'title'       => 'Lightning Upload',
                        'description' => 'Drag-and-drop or paste a URL. GIFs are processed and ready in seconds — no compression guesswork.',
                        'icon'        => 'upload',
                        'color'       => 'indigo',
                    ],
                    [
                        'title'       => 'Smart Search',
                        'description' => 'Find any GIF instantly with intelligent tagging, fuzzy matching, and saved searches.',
                        'icon'        => 'search',
                        'color'       => 'violet',
                    ],
                    [
                        'title'       => 'Auto Categories',
                        'description' => 'GIFs are sorted into categories automatically, so your library stays organized as it grows.',
                        'icon'        => 'folder',
                        'color'       => 'cyan',
                    ],
                    [
                        'title'       => 'One-Click Share',
                        'description' => 'Generate shareable links or embed codes instantly. Push to Slack, Discord, or anywhere.',
                        'icon'        => 'share',
                        'color'       => 'indigo',
                    ],
                    [
                        'title'       => 'Collections',
                        'description' => 'Bundle related GIFs into curated collections for projects, moods, or campaigns.',
                        'icon'        => 'collection',
                        'color'       => 'violet',
                    ],
                    [
                        'title'       => 'Blazing CDN',
                        'description' => 'Every GIF is served from a global edge network for instant playback, anywhere on earth.',
                        'icon'        => 'bolt',
                        'color'       => 'cyan',
                    ],
                ];

                // Per-colour glow classes (kept in PHP so Tailwind sees them at build time)
                $glow = [
                    'indigo' => 'group-hover:shadow-indigo-500/20 group-hover:border-indigo-500/40',
                    'violet' => 'group-hover:shadow-violet-500/20 group-hover:border-violet-500/40',
                    'cyan'   => 'group-hover:shadow-cyan-500/20 group-hover:border-cyan-500/40',
                ];
                $iconBg = [
                    'indigo' => 'from-indigo-500/20 to-indigo-500/5 text-indigo-300',
                    'violet' => 'from-violet-500/20 to-violet-500/5 text-violet-300',
                    'cyan'   => 'from-cyan-500/20 to-cyan-500/5 text-cyan-300',
                ];

                // Inline SVG paths per icon
                $icons = [
                    'upload' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>',
                    'search' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
                    'folder' => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>',
                    'share'  => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>',
                    'collection' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" border-radius="1" width="7" height="7" rx="1"/>',
                    'bolt'   => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
                ];
            @endphp

            @foreach ($features as $index => $feature)
                <article
                    data-reveal
                    data-reveal-delay="{{ ($index % 3) + 1 }}"
                    class="group glass relative overflow-hidden rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 {{ $glow[$feature['color']] }} hover:shadow-2xl"
                >
                    {{-- Icon --}}
                    <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br {{ $iconBg[$feature['color']] }}">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            {!! $icons[$feature['icon']] !!}
                        </svg>
                    </div>

                    {{-- Title + description --}}
                    <h3 class="mb-2 text-lg font-bold text-white">{{ $feature['title'] }}</h3>
                    <p class="text-sm leading-relaxed text-slate-400">{{ $feature['description'] }}</p>

                    {{-- Hover sheen: a diagonal light sweep on hover --}}
                    <div class="pointer-events-none absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/5 to-transparent transition-transform duration-700 group-hover:translate-x-full" aria-hidden="true"></div>
                </article>
            @endforeach

        </div>
    </div>
</section>
