{{--
    Testimonials Section — Task 28
    ────────────────────────────────────────────────────────────
    • Horizontal drag-scroll carousel (CSS scroll-snap, no JS library)
    • Alpine.js for prev/next buttons and active-dot tracking
    • Each card: glassmorphism, avatar, name, role, quote, star rating
    • scroll-reveal on section entry via data-reveal / IntersectionObserver
    ────────────────────────────────────────────────────────────
--}}
<section
    id="testimonials"
    aria-label="Testimonials"
    class="relative py-28 overflow-hidden"
>
    {{-- subtle top separator glow --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-px"
         style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.4), transparent);"></div>

    {{-- background glow --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0" style="z-index:0;">
        <div style="position:absolute; bottom:-10%; right:-5%;
                    width:min(500px,50vw); height:min(500px,50vw);
                    background:radial-gradient(circle, rgba(139,92,246,0.12) 0%, transparent 65%);
                    filter:blur(72px);"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-6" style="z-index:1;">

        {{-- Section header --}}
        <div class="text-center mb-16" data-reveal>
            <p class="text-sm font-semibold tracking-widest uppercase mb-3"
               style="color: var(--landing-primary);">What people say</p>
            <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-white">
                Loved by creators
            </h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto">
                Thousands of people use GIF Manager every day to organise and share their collections.
            </p>
        </div>

        {{-- Carousel --}}
        @php
        $testimonials = [
            [
                'name'   => 'Sarah Kim',
                'role'   => 'Content Creator',
                'avatar' => 'SK',
                'color'  => '#6366f1',
                'stars'  => 5,
                'quote'  => 'Finally a tool that actually keeps my GIF library tidy. The smart search alone saves me 20 minutes a day.',
            ],
            [
                'name'   => 'Marcus Reid',
                'role'   => 'UI Designer',
                'avatar' => 'MR',
                'color'  => '#8b5cf6',
                'stars'  => 5,
                'quote'  => 'The auto-categorize feature is genuinely magic. I dumped 3,000 GIFs in and it sorted them perfectly.',
            ],
            [
                'name'   => 'Priya Nair',
                'role'   => 'Social Media Manager',
                'avatar' => 'PN',
                'color'  => '#06b6d4',
                'stars'  => 5,
                'quote'  => 'One-click sharing to any platform changed how I work. My engagement went up 40% since I started using it.',
            ],
            [
                'name'   => 'Tom Erikson',
                'role'   => 'Indie Developer',
                'avatar' => 'TE',
                'color'  => '#f59e0b',
                'stars'  => 5,
                'quote'  => 'Clean, fast, and zero bloat. This is exactly what a GIF manager should be — nothing more, nothing less.',
            ],
            [
                'name'   => 'Lena Schulz',
                'role'   => 'Motion Designer',
                'avatar' => 'LS',
                'color'  => '#10b981',
                'stars'  => 5,
                'quote'  => 'I\'ve tried every GIF manager out there. This is the only one that doesn\'t get in my way.',
            ],
        ];
        @endphp

        <div
            x-data="{
                active: 0,
                total: {{ count($testimonials) }},
                prev() { this.active = (this.active - 1 + this.total) % this.total; this.scrollTo(this.active); },
                next() { this.active = (this.active + 1) % this.total; this.scrollTo(this.active); },
                scrollTo(i) {
                    const track = $refs.track;
                    const card  = track.children[i];
                    if (!card) return;
                    track.scrollTo({ left: card.offsetLeft - (track.offsetWidth - card.offsetWidth) / 2, behavior: 'smooth' });
                },
                onScroll() {
                    const track = $refs.track;
                    const mid   = track.scrollLeft + track.offsetWidth / 2;
                    let closest = 0, minDist = Infinity;
                    Array.from(track.children).forEach((c, i) => {
                        const d = Math.abs(c.offsetLeft + c.offsetWidth / 2 - mid);
                        if (d < minDist) { minDist = d; closest = i; }
                    });
                    this.active = closest;
                }
            }"
            data-reveal
            data-reveal-delay="2"
        >
            {{-- Track --}}
            <div
                x-ref="track"
                @scroll.passive="onScroll()"
                class="flex gap-6 overflow-x-auto pb-6"
                style="
                    scroll-snap-type: x mandatory;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-width: none;
                "
            >
                @foreach($testimonials as $i => $t)
                <article
                    class="flex-shrink-0 flex flex-col justify-between rounded-2xl p-7 transition-transform duration-300"
                    style="
                        scroll-snap-align: center;
                        width: min(360px, 80vw);
                        background: rgba(255,255,255,0.04);
                        border: 1px solid rgba(255,255,255,0.08);
                        backdrop-filter: blur(16px);
                        -webkit-backdrop-filter: blur(16px);
                    "
                    aria-label="Testimonial from {{ $t['name'] }}"
                >
                    {{-- Stars --}}
                    <div class="flex gap-0.5 mb-5" aria-label="{{ $t['stars'] }} out of 5 stars">
                        @for($s = 0; $s < $t['stars']; $s++)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b" aria-hidden="true">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>

                    {{-- Quote --}}
                    <blockquote class="text-slate-300 leading-relaxed text-[0.95rem] mb-7 flex-1">
                        "{{ $t['quote'] }}"
                    </blockquote>

                    {{-- Author --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                             style="background: {{ $t['color'] }};">
                            {{ $t['avatar'] }}
                        </div>
                        <div>
                            <div class="text-white font-semibold text-sm">{{ $t['name'] }}</div>
                            <div class="text-slate-500 text-xs">{{ $t['role'] }}</div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Controls --}}
            <div class="flex items-center justify-center gap-6 mt-8">

                {{-- Prev --}}
                <button
                    @click="prev()"
                    class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                    style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.10);"
                    onmouseover="this.style.background='rgba(255,255,255,0.12)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.06)'"
                    aria-label="Previous testimonial"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" class="text-slate-400" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>

                {{-- Dots --}}
                <div class="flex gap-2" role="tablist" aria-label="Testimonial navigation">
                    @foreach($testimonials as $i => $t)
                    <button
                        @click="active = {{ $i }}; scrollTo({{ $i }})"
                        :class="active === {{ $i }}
                            ? 'w-6 bg-indigo-500'
                            : 'w-2 bg-white/20 hover:bg-white/40'"
                        class="h-2 rounded-full transition-all duration-300"
                        role="tab"
                        :aria-selected="active === {{ $i }}"
                        aria-label="Go to testimonial {{ $i + 1 }}"
                    ></button>
                    @endforeach
                </div>

                {{-- Next --}}
                <button
                    @click="next()"
                    class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                    style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.10);"
                    onmouseover="this.style.background='rgba(255,255,255,0.12)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.06)'"
                    aria-label="Next testimonial"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" class="text-slate-400" aria-hidden="true">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>

            </div>
        </div>
    </div>
</section>
