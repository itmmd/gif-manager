{{--
    Shared GIF Card Component
    --------------------------------------------------------------------------
    Used in both the public gallery (/gifs) and the Landing Showcase section.

    Props:
      $href      — URL of the detail page (route('gifs.show', $gif))
      $url       — Public URL of the GIF/MP4 file
      $title     — Display title (already sanitised by Gif model mutator)
      $mimeType  — 'image/gif' | 'video/mp4'
      $size      — Formatted file size string, e.g. "1.2 MB" (optional)
      $reveal    — Whether to add data-reveal scroll animation (default false)
      $delay     — data-reveal-delay value 1–4 (default 1)

    Every card:
      - Fixed 1:1 aspect-ratio container (aspect-square + overflow-hidden)
      - object-cover + object-center → uniform crop, no stretching
      - Title clamped to 1 line (truncate) → never breaks layout
      - Slide-up overlay on hover (translate-y trick, no opacity flicker)
      - Accessible: <a> wraps the whole card, aria-label on media element
--}}
@props([
    'href'     => '#',
    'url'      => '',
    'title'    => '',
    'mimeType' => 'image/gif',
    'size'     => null,
    'reveal'   => false,
    'delay'    => 1,
])

<a
    href="{{ $href }}"
    @if ($reveal) data-reveal data-reveal-delay="{{ $delay }}" @endif
    class="group relative block overflow-hidden rounded-2xl border border-white/8 bg-slate-800/60 transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-500/40 hover:shadow-xl hover:shadow-indigo-500/15 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
    aria-label="{{ e($title) }}"
>
    {{-- ── Fixed 1:1 container ── --}}
    <div class="relative aspect-square overflow-hidden">

        @if ($mimeType === 'video/mp4')
            <video
                src="{{ $url }}"
                muted autoplay loop playsinline
                class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-[1.05]"
                aria-label="{{ e($title) }}"
            ></video>
        @else
            <img
                src="{{ $url }}"
                alt="{{ e($title) }}"
                loading="lazy"
                class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-[1.05]"
            >
        @endif

        {{-- ── Slide-up overlay ── --}}
        <div class="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3 transition-transform duration-200 ease-out group-hover:translate-y-0">
            {{-- truncate + line-clamp-1 → long titles never break the card --}}
            <p class="truncate text-xs font-semibold leading-tight text-white">{{ $title }}</p>
            @if ($size)
                <p class="mt-0.5 truncate text-[10px] text-slate-400">{{ $size }}</p>
            @endif
        </div>

    </div>
</a>
