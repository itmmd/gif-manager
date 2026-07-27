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

    <div class="overflow-hidden px-2.5 py-2 space-y-0.5">
        <p
            class="line-clamp-1 overflow-hidden text-sm font-medium leading-snug text-white/90"
            dir="rtl"
            style="direction:rtl"
            title="{{ e($title) }}"
        >{{ $title }}</p>

        @if ($size)
            <p class="text-xs font-normal text-white/40">{{ $size }}</p>
        @endif
    </div>
</div>
