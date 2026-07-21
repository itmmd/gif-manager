{{-- Public GIF detail page --}}
<div>
    <div class="mx-auto max-w-5xl px-6 py-16 lg:px-8">

        {{-- Back --}}
        <a href="{{ route('gifs.index') }}"
           class="mb-8 inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Back to Gallery
        </a>

        <div class="grid gap-10 lg:grid-cols-[1fr_280px]">

            {{-- ── Main media ── --}}
            <div>
                <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
                    @if ($gif->mime_type === 'video/mp4')
                        <video
                            src="{{ $gif->url }}"
                            controls
                            autoplay
                            loop
                            muted
                            playsinline
                            class="w-full"
                            aria-label="{{ e($gif->title) }}"
                        ></video>
                    @else
                        <img
                            src="{{ $gif->url }}"
                            alt="{{ e($gif->title) }}"
                            class="w-full"
                        >
                    @endif
                </div>
            </div>

            {{-- ── Sidebar info + actions ── --}}
            <aside class="flex flex-col gap-6">

                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $gif->title }}</h1>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $gif->formatted_size }}
                        &middot;
                        {{ strtoupper(pathinfo($gif->file_path, PATHINFO_EXTENSION)) }}
                        &middot;
                        {{ $gif->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Download --}}
                <a
                    href="{{ $gif->url }}"
                    download="{{ $gif->original_filename }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-indigo-500/50"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download
                </a>

                {{-- Copy link --}}
                <div x-data="{ copied: false }">
                    <p class="mb-1.5 text-xs font-medium uppercase tracking-wider text-slate-500">Direct link</p>
                    <div class="flex overflow-hidden rounded-lg border border-white/10">
                        <input
                            type="text"
                            readonly
                            value="{{ $gif->url }}"
                            class="min-w-0 flex-1 bg-white/5 px-3 py-2 text-xs text-slate-300 focus:outline-none"
                            aria-label="Direct link to file"
                        >
                        <button
                            @click="
                                navigator.clipboard.writeText('{{ $gif->url }}');
                                copied = true;
                                setTimeout(() => copied = false, 2000);
                            "
                            class="shrink-0 border-l border-white/10 bg-white/5 px-3 text-xs text-slate-400 transition hover:bg-white/10 hover:text-white"
                            aria-label="Copy link"
                        >
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied" class="text-green-400">Copied!</span>
                        </button>
                    </div>
                </div>

            </aside>
        </div>

        {{-- ── Related GIFs ── --}}
        @if ($related->isNotEmpty())
            <section class="mt-16">
                <h2 class="mb-6 text-lg font-bold text-white">More GIFs</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-8">
                    @foreach ($related as $item)
                        <a href="{{ route('gifs.show', $item) }}"
                           class="group block overflow-hidden rounded-xl border border-white/5 hover:border-indigo-500/40 transition">
                            @if ($item->mime_type === 'video/mp4')
                                <video src="{{ $item->url }}" muted autoplay loop playsinline
                                       class="aspect-square w-full object-cover"></video>
                            @else
                                <img src="{{ $item->url }}" alt="{{ e($item->title) }}"
                                     loading="lazy" class="aspect-square w-full object-cover">
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</div>
