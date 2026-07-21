{{--
    Showcase Section
    --------------------------------------------------------------------------
    Displays up to 8 of the latest real GIFs from the database.
    Falls back to placeholder gradient cards when no GIFs exist yet.

    $showcaseGifs is injected by Landing.php via GifShowcaseInterface
    (Core contract) — Landing never imports the Gif model directly.
--}}
@php
    /** @var \Illuminate\Support\Collection $showcaseGifs */
    $hasRealGifs = isset($showcaseGifs) && $showcaseGifs->isNotEmpty();

    // Placeholder cards shown when DB is empty or service returns nothing.
    $placeholders = [
        ['label' => 'Gaming Reactions',   'gradient' => 'from-indigo-600/50 to-violet-700/40', 'h' => 'h-56'],
        ['label' => 'Team Memes',         'gradient' => 'from-violet-600/50 to-fuchsia-600/40','h' => 'h-44'],
        ['label' => 'Tech Demos',         'gradient' => 'from-cyan-600/50 to-blue-700/40',    'h' => 'h-64'],
        ['label' => 'Celebrations',       'gradient' => 'from-pink-600/50 to-rose-600/40',    'h' => 'h-48'],
        ['label' => 'Nature & Travel',    'gradient' => 'from-emerald-600/50 to-teal-700/40', 'h' => 'h-56'],
        ['label' => 'UI Animations',      'gradient' => 'from-amber-600/50 to-orange-700/40', 'h' => 'h-52'],
        ['label' => 'Product Mockups',    'gradient' => 'from-indigo-600/50 to-blue-600/40',  'h' => 'h-44'],
        ['label' => 'Tutorial Steps',     'gradient' => 'from-violet-600/50 to-purple-700/40','h' => 'h-60'],
    ];
@endphp

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
        <div class="mx-auto mt-16 max-w-6xl columns-1 gap-5 sm:columns-2 lg:columns-3 xl:columns-4">

            @if ($hasRealGifs)
                {{-- Real GIFs from the database --}}
                @foreach ($showcaseGifs as $i => $gif)
                    <a
                        href="{{ $gif->show_url }}"
                        data-reveal
                        data-reveal-delay="{{ ($i % 3) + 1 }}"
                        class="group relative mb-5 block break-inside-avoid overflow-hidden rounded-2xl"
                        aria-label="{{ e($gif->title) }}"
                    >
                        <div class="relative w-full overflow-hidden rounded-2xl bg-slate-800/60 ring-1 ring-inset ring-white/10">
                            @if ($gif->mime_type === 'video/mp4')
                                <video
                                    src="{{ $gif->url }}"
                                    muted autoplay loop playsinline
                                    class="w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.03]"
                                    aria-label="{{ e($gif->title) }}"
                                ></video>
                            @else
                                <img
                                    src="{{ $gif->url }}"
                                    alt="{{ e($gif->title) }}"
                                    loading="lazy"
                                    class="w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.03]"
                                >
                            @endif

                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 flex items-end rounded-2xl bg-gradient-to-t from-black/80 via-black/20 to-transparent p-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <p class="truncate text-sm font-semibold text-white">{{ $gif->title }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach

            @else
                {{-- Placeholder cards (no GIFs uploaded yet) --}}
                @foreach ($placeholders as $i => $item)
                    <div
                        data-reveal
                        data-reveal-delay="{{ ($i % 3) + 1 }}"
                        class="group relative mb-5 break-inside-avoid overflow-hidden rounded-2xl"
                    >
                        <div class="{{ $item['h'] }} w-full rounded-2xl bg-gradient-to-br {{ $item['gradient'] }} ring-1 ring-inset ring-white/10 transition-transform duration-500 ease-out group-hover:scale-[1.03]">
                            <div class="flex h-full items-center justify-center">
                                <svg class="h-10 w-10 text-white/15 transition-transform duration-500 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute inset-0 flex items-end rounded-2xl bg-gradient-to-t from-black/80 via-black/20 to-transparent p-5 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <p class="text-sm font-semibold text-white">{{ $item['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

        {{-- ── View all button ── --}}
        <div
            data-reveal
            data-reveal-delay="2"
            class="mt-12 text-center"
        >
            <a
                href="{{ route('gifs.index') }}"
                class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-indigo-500/50"
            >
                View all GIFs
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

    </div>

    {{-- ── Smooth bottom transition into next section ── --}}
    <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#0a0a0f] to-transparent" aria-hidden="true"></div>

</section>
