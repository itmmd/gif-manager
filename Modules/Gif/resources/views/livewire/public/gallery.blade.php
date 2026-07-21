{{-- Public GIF Gallery — dark landing theme --}}
<div>

    {{-- ── Hero bar ── --}}
    <section class="relative overflow-hidden py-20 text-center">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true"
             style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99,102,241,0.18), transparent 65%);">
        </div>
        <div class="relative z-10 mx-auto max-w-3xl px-6">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl type-display">
                GIF <span class="text-gradient">Gallery</span>
            </h1>
            <p class="mt-4 text-lg text-slate-400">
                Browse and download free GIFs. No account required.
            </p>

            {{-- Search --}}
            <div class="mx-auto mt-8 max-w-md">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search GIFs…"
                        class="w-full rounded-xl border border-white/10 bg-white/5 py-3 pl-11 pr-4 text-sm text-white placeholder-slate-500 backdrop-blur focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40"
                        aria-label="Search GIFs"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- ── Grid ── --}}
    <section class="mx-auto max-w-7xl px-6 pb-24 lg:px-8">

        @if ($gifs->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg class="mb-4 h-16 w-16 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                </svg>
                <p class="text-lg font-semibold text-white">No GIFs found</p>
                @if ($search)
                    <p class="mt-1 text-sm text-slate-400">No results for "<em>{{ e($search) }}</em>"</p>
                    <button wire:click="$set('search','')"
                            class="mt-4 text-sm text-indigo-400 hover:text-indigo-300">
                        Clear search
                    </button>
                @endif
            </div>

        @else
            {{-- Masonry-style responsive grid --}}
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6"
                 wire:loading.class="opacity-50">

                @foreach ($gifs as $gif)
                    <a
                        href="{{ route('gifs.show', $gif) }}"
                        class="group relative block overflow-hidden rounded-xl border border-white/8 bg-white/3 transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-500/40 hover:shadow-xl hover:shadow-indigo-500/10"
                        aria-label="{{ e($gif->title) }}"
                    >
                        {{-- Fixed aspect-ratio container — prevents giant single items --}}
                        <div class="relative aspect-square overflow-hidden bg-slate-800/60">
                            @if ($gif->mime_type === 'video/mp4')
                                <video
                                    src="{{ $gif->url }}"
                                    muted autoplay loop playsinline
                                    class="absolute inset-0 h-full w-full object-cover"
                                    aria-label="{{ e($gif->title) }}"
                                ></video>
                            @else
                                <img
                                    src="{{ $gif->url }}"
                                    alt="{{ e($gif->title) }}"
                                    loading="lazy"
                                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                >
                            @endif

                            {{-- Hover overlay with title --}}
                            <div class="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3 transition-transform duration-200 group-hover:translate-y-0">
                                <p class="truncate text-xs font-medium text-white">{{ $gif->title }}</p>
                                <p class="mt-0.5 text-[10px] text-slate-400">{{ $gif->formatted_size }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>

            {{-- Pagination --}}
            @if ($gifs->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $gifs->links() }}
                </div>
            @endif
        @endif
    </section>

</div>
