{{--
    FAQ Section — Task 28
    ────────────────────────────────────────────────────────────
    • Alpine.js x-data accordion — one open at a time
    • Animated height via CSS max-height transition (no JS height calc)
    • Keyboard accessible: Enter/Space toggle, proper aria-expanded
    • scroll-reveal on section entry
    ────────────────────────────────────────────────────────────
--}}
<section
    id="faq"
    aria-label="Frequently Asked Questions"
    class="relative py-28 overflow-hidden"
>
    {{-- top separator glow --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-px"
         style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.3), transparent);"></div>

    {{-- background glow --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0" style="z-index:0;">
        <div style="position:absolute; top:-10%; left:-5%;
                    width:min(450px,45vw); height:min(450px,45vw);
                    background:radial-gradient(circle, rgba(99,102,241,0.10) 0%, transparent 65%);
                    filter:blur(80px);"></div>
    </div>

    <div class="relative max-w-2xl mx-auto px-6" style="z-index:1;">

        {{-- Section header --}}
        <div class="text-center mb-16" data-reveal>
            <p class="text-sm font-semibold tracking-widest uppercase mb-3"
               style="color: var(--landing-primary);">Got questions?</p>
            <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-white">
                Frequently asked
            </h2>
            <p class="mt-4 text-slate-400">
                Everything you need to know about GIF Manager.
                Can't find the answer? <a href="mailto:hello@gifmanager.app"
                class="text-indigo-400 hover:text-indigo-300 underline underline-offset-2 transition-colors">Send us a message.</a>
            </p>
        </div>

        @php
        $faqs = [
            [
                'q' => 'Is GIF Manager free to use?',
                'a' => 'Yes — the core features (upload, organise, search, share) are completely free. We offer a Pro plan for power users who need unlimited storage, advanced analytics, and team collaboration features.',
            ],
            [
                'q' => 'How does the auto-categorize feature work?',
                'a' => 'When you upload a GIF, our system analyses the content and automatically assigns relevant tags and categories. You can always edit or override these — it\'s a starting point, not a cage.',
            ],
            [
                'q' => 'What file formats are supported?',
                'a' => 'We support GIF, WebP (animated), APNG, and MP4 converted to GIF. Maximum file size is 50 MB on the free plan and 200 MB on Pro.',
            ],
            [
                'q' => 'Can I share GIFs with people who don\'t have an account?',
                'a' => 'Absolutely. Every GIF gets a unique shareable link. Recipients can view and download without signing up. You can also set links to expire or be password-protected.',
            ],
            [
                'q' => 'Is my content private by default?',
                'a' => 'Yes. All uploads are private by default. You explicitly choose what to make public, share via link, or keep entirely private. We never surface your content to other users without your permission.',
            ],
            [
                'q' => 'Can I import my existing GIF collection?',
                'a' => 'Yes — you can bulk-upload a folder of GIFs, import from a ZIP archive, or connect to Giphy and Tenor to migrate your saved collections.',
            ],
        ];
        @endphp

        {{-- Accordion --}}
        <dl
            x-data="{ open: null }"
            class="space-y-3"
            data-reveal
            data-reveal-delay="2"
        >
            @foreach($faqs as $i => $faq)
            <div
                class="rounded-2xl overflow-hidden transition-all duration-200"
                style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);"
                :style="open === {{ $i }}
                    ? 'border-color: rgba(99,102,241,0.35); background: rgba(99,102,241,0.06);'
                    : ''"
            >
                {{-- Question button --}}
                <dt>
                    <button
                        type="button"
                        class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left"
                        @click="open = open === {{ $i }} ? null : {{ $i }}"
                        :aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                        aria-controls="faq-answer-{{ $i }}"
                        id="faq-btn-{{ $i }}"
                    >
                        <span class="font-semibold text-white text-[0.95rem] leading-snug">
                            {{ $faq['q'] }}
                        </span>

                        {{-- Chevron icon — rotates when open --}}
                        <span
                            class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-all duration-300"
                            style="background:rgba(255,255,255,0.07);"
                            :style="open === {{ $i }} ? 'background:rgba(99,102,241,0.25);' : ''"
                            aria-hidden="true"
                        >
                            <svg
                                width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5"
                                class="text-slate-400 transition-transform duration-300"
                                :class="open === {{ $i }} ? 'rotate-180 !text-indigo-400' : ''"
                            >
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </span>
                    </button>
                </dt>

                {{-- Answer — animated height via max-height --}}
                <dd
                    id="faq-answer-{{ $i }}"
                    role="region"
                    :aria-labelledby="'faq-btn-{{ $i }}'"
                    class="overflow-hidden transition-all duration-300 ease-in-out"
                    :style="open === {{ $i }}
                        ? 'max-height: 400px; opacity: 1;'
                        : 'max-height: 0;    opacity: 0;'"
                    style="max-height: 0; opacity: 0;"
                >
                    <p class="px-6 pb-6 text-slate-400 leading-relaxed text-sm">
                        {{ $faq['a'] }}
                    </p>
                </dd>
            </div>
            @endforeach
        </dl>

    </div>
</section>
