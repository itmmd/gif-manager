{{--
    Shared GIF Card Component
    --------------------------------------------------------------------------
    Used in the public gallery (/gifs), Landing Showcase, and Related section.

    Props:
      $href      — URL of the detail page (route('gifs.show', $gif))
      $url       — Public URL of the GIF/MP4 file
      $title     — Display title (already sanitised by Gif model mutator)
      $mimeType  — 'image/gif' | 'video/mp4'
      $size      — Formatted file size string, e.g. "1.2 MB"
                   Pass only in admin contexts — omit in public Gallery/Showcase.
                   File size is a technical/admin metric, not useful for end users.
      $reveal    — Whether to add data-reveal scroll animation (default false)
      $delay     — data-reveal-delay value 1–4 (default 1)

    Typography hierarchy:
      - Title: text-sm font-medium text-white/90 — primary, what the eye hits first
      - Size:  text-xs text-white/40             — secondary, muted, clearly subordinate
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

<div
    @if ($reveal) data-reveal data-reveal-delay="{{ $delay }}" @endif
    class="group flex flex-col overflow-hidden rounded-2xl border border-white/8 bg-slate-800/60 transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-500/40 hover:shadow-xl hover:shadow-indigo-500/15 focus-within:ring-2 focus-within:ring-indigo-500"
>
    {{-- ── Fixed 1:1 image container ── --}}
    <a
        href="{{ $href }}"
        class="relative block aspect-square overflow-hidden focus-visible:outline-none"
        aria-label="{{ e($title) }}"
        tabindex="0"
    >
        @if ($mimeType === 'video/mp4')
            <video
                src="{{ $url }}"
                muted autoplay loop playsinline
                class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-[1.05]"
                aria-hidden="true"
            ></video>
        @else
            <img
                src="{{ $url }}"
                alt="{{ e($title) }}"
                loading="lazy"
                class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-[1.05]"
            >
        @endif
    </a>

    {{-- ── Caption bar — always visible below the image ── --}}
    {{--
        overflow-hidden on this wrapper is a safety net independent of
        line-clamp — if the Tailwind line-clamp plugin is inactive or
        the browser ignores it, no child can escape the card boundary.
    --}}
    <div class="overflow-hidden px-2.5 py-2 space-y-0.5">

        {{--
            Title: primary — text-sm font-medium, bright white.
            dir="rtl" + style="direction:rtl" must be on the <p> itself,
            not just the parent, so the browser places the ellipsis on the
            correct (right) side for Persian/Arabic titles.
            overflow-hidden is written explicitly as a second guard in case
            line-clamp is silently ignored.
        --}}
        <p
            class="line-clamp-1 overflow-hidden text-sm font-medium leading-snug text-white/90"
            dir="rtl"
            style="direction:rtl"
            title="{{ e($title) }}"
        >{{ $title }}</p>

        {{--
            File size: secondary / admin-only metadata.
            Shown only when $size is explicitly passed (admin views).
            Visually subordinate: smaller, much dimmer than the title.
        --}}
        @if ($size)
            <p class="text-xs font-normal text-white/40">{{ $size }}</p>
        @endif

    </div>
</div>
