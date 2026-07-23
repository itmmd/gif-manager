{{-- GIF Genie — AI-powered semantic search page, landing theme --}}
<div>
    <x-landing::navbar />

    {{-- ── Hero / search bar ── --}}
    <section class="relative overflow-hidden py-20 text-center">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true"
             style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(139,92,246,0.20), transparent 65%);">
        </div>

        <div class="relative z-10 mx-auto max-w-3xl px-6">
            {{-- Genie icon --}}
            <div class="mb-5 inline-flex items-center justify-center rounded-2xl bg-violet-600/20 p-4 ring-1 ring-violet-500/30" aria-hidden="true">
                <svg class="h-9 w-9 text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2a5 5 0 015 5c0 5.25-5 10-5 10S7 12.25 7 7a5 5 0 015-5z"/>
                    <circle cx="12" cy="7" r="2"/>
                    <path d="M8 22h8M12 16v6"/>
                </svg>
            </div>

            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl type-display">
                GIF <span class="text-gradient">Genie</span>
            </h1>
            <p class="mt-4 text-lg text-slate-400">
                Describe what you're looking for in plain language — AI does the rest.
            </p>

            {{-- Search input --}}
            <div class="mx-auto mt-8 max-w-xl">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-violet-400"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M12 2a5 5 0 015 5c0 5.25-5 10-5 10S7 12.25 7 7a5 5 0 015-5z"/>
                        <circle cx="12" cy="7" r="2"/>
                    </svg>
                    <input
                        type="search"
                        wire:model.live.debounce.500ms="query"
                        placeholder="e.g. scared cat running away, someone celebrating…"
                        class="w-full rounded-2xl border border-violet-500/30 bg-white/5 py-4 pl-12 pr-5 text-sm text-white placeholder-slate-500 backdrop-blur focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/40"
                        aria-label="Describe the GIF you are looking for"
                        maxlength="200"
                        autocomplete="off"
                        spellcheck="false"
                    >
                    {{-- Loading spinner --}}
                    <div wire:loading wire:target="query"
                         class="absolute right-4 top-1/2 -translate-y-1/2"
                         aria-label="Searching…" role="status">
                        <svg class="h-4 w-4 animate-spin text-violet-400" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>

                {{-- Search mode badge --}}
                @if ($query !== '')
                    <p class="mt-3 text-xs text-slate-500" aria-live="polite">
                        @if ($usedSemanticSearch)
                            <span class="inline-flex items-center gap-1 rounded-full bg-violet-900/40 px-2.5 py-0.5 text-violet-300 ring-1 ring-violet-700/50">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2a5 5 0 015 5c0 5.25-5 10-5 10S7 12.25 7 7a5 5 0 015-5z"/><circle cx="12" cy="7" r="2"/></svg>
                                Semantic AI search
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-800 px-2.5 py-0.5 text-slate-400 ring-1 ring-white/10">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                Text search
                            </span>
                        @endif
                        &nbsp;for &ldquo;{{ e($query) }}&rdquo;
                    </p>
                @endif

                {{-- Link back to standard gallery --}}
                <div class="mt-4">
                    <a href="{{ route('gifs.index') }}"
                       class="text-xs text-slate-500 underline decoration-dotted hover:text-slate-300">
                        ← Back to full gallery
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Results grid ── --}}
    <section class="mx-auto max-w-7xl px-6 pb-24 lg:px-8">

        @if ($gifs->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg class="mb-4 h-16 w-16 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M12 2a5 5 0 015 5c0 5.25-5 10-5 10S7 12.25 7 7a5 5 0 015-5z"/>
                    <circle cx="12" cy="7" r="2"/>
                </svg>
                <p class="text-lg font-semibold text-white">
                    @if ($query) No GIFs matched "{{ e($query) }}" @else No GIFs available yet @endif
                </p>
                @if ($query)
                    <p class="mt-1 text-sm text-slate-400">Try a different description, or
                        <button wire:click="$set('query', '')" class="text-violet-400 hover:text-violet-300 underline">browse all GIFs</button>.
                    </p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6"
                 wire:loading.class="opacity-50"
                 wire:target="query">
                @foreach ($gifs as $gif)
                    <x-gif::gif-card
                        :href="route('gifs.show', $gif)"
                        :url="$gif->url"
                        :title="$gif->title"
                        :mimeType="$gif->mime_type"
                    />
                @endforeach
            </div>

            @if ($gifs->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $gifs->links() }}
                </div>
            @endif
        @endif

    </section>
</div>
