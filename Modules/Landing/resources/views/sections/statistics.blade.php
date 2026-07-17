{{--
    Statistics Section  (Task 27)
    --------------------------------------------------------------------------
    Requirements covered:
      - Counter animation: numbers animate from 0 → final value when the
        section enters the viewport, driven by Alpine.js x-intersect.
      - Minimal design, large bold typography on numbers.

    Approach:
      Each counter uses x-intersect:enter.once on its own wrapper to start
      a requestAnimationFrame loop. Simple, self-contained, no parent state.
    --}}
<section
    id="statistics"
    aria-label="Statistics"
    class="relative overflow-hidden py-28"
>

    {{-- Subtle top fade from previous section --}}
    <div class="pointer-events-none absolute top-0 left-0 right-0 -z-10 h-24 bg-gradient-to-b from-[#0a0a0f] to-transparent" aria-hidden="true"></div>

    <div class="relative z-10 mx-auto max-w-5xl px-6 lg:px-8">

        {{-- Section header --}}
        <div class="mx-auto mb-16 max-w-2xl text-center">
            <p
                data-reveal
                class="glass mb-4 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wider text-indigo-300"
            >
                Stats
            </p>
            <h2
                data-reveal
                data-reveal-delay="1"
                class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl"
            >
                Trusted by
                <span class="text-gradient">thousands</span>
            </h2>
        </div>

        {{-- ── Counter grid ── --}}
        <div class="grid grid-cols-2 gap-8 lg:grid-cols-4">

            @php
                $stats = [
                    ['value' => 10000, 'suffix' => '+', 'label' => 'GIFs Uploaded', 'duration' => 2000],
                    ['value' => 5000,  'suffix' => '+', 'label' => 'Active Users',  'duration' => 1800],
                    ['value' => 120,   'suffix' => '+', 'label' => 'Categories',    'duration' => 1400],
                    ['value' => 99.9,  'suffix' => '%',  'label' => 'Uptime',       'duration' => 1600],
                ];
            @endphp

            @foreach ($stats as $i => $stat)
                <div
                    data-reveal
                    data-reveal-delay="{{ $i + 1 }}"
                    x-data="{
                        current: 0,
                        target: {{ $stat['value'] }},
                        dur: {{ $stat['duration'] }},
                        done: false,
                        run() {
                            if (this.done) return;
                            this.done = true;
                            const t0 = performance.now();
                            const step = (now) => {
                                const p = Math.min((now - t0) / this.dur, 1);
                                const eased = 1 - Math.pow(1 - p, 3);
                                this.current = this.target * eased;
                                if (p < 1) requestAnimationFrame(step);
                            };
                            requestAnimationFrame(step);
                        }
                    }"
                    x-intersect:enter.once="run()"
                    class="group relative text-center"
                >
                    {{-- Divider line (not on mobile, not after last) --}}
                    @if ($i < 3)
                        <div class="pointer-events-none absolute right-0 top-1/2 hidden h-12 w-px -translate-y-1/2 bg-white/10 lg:block" aria-hidden="true"></div>
                    @endif

                    {{-- Animated number --}}
                    <div class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        <span x-text="current < 10 ? current.toFixed(1) : Math.round(current).toLocaleString()"></span><span class="text-gradient">{{ $stat['suffix'] }}</span>
                    </div>

                    {{-- Label --}}
                    <p class="mt-3 text-sm font-medium text-slate-400">{{ $stat['label'] }}</p>
                </div>
            @endforeach

        </div>
    </div>

    {{-- Bottom fade toward next section --}}
    <div class="pointer-events-none absolute bottom-0 left-0 right-0 -z-10 h-24 bg-gradient-to-t from-[#0a0a0f] to-transparent" aria-hidden="true"></div>

</section>
