@php
    $f            = config('landing.faqs');
    $faqs         = $f['items'];
    $contactEmail = config('landing.brand.contact_email');
@endphp

<section
    id="faq"
    aria-label="Frequently Asked Questions"
    class="relative py-28 overflow-hidden"
>
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-px"
         style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.3), transparent);"></div>

    <div aria-hidden="true" class="pointer-events-none absolute inset-0" style="z-index:0;">
        <div style="position:absolute; top:-10%; left:-5%;
                    width:min(450px,45vw); height:min(450px,45vw);
                    background:radial-gradient(circle, rgba(99,102,241,0.10) 0%, transparent 65%);
                    filter:blur(80px);"></div>
    </div>

    <div class="relative max-w-2xl mx-auto px-6" style="z-index:1;">

        <div class="text-center mb-16" data-reveal>
            <p class="text-sm font-semibold tracking-widest uppercase mb-3" style="color: var(--landing-primary);">{{ $f['eyebrow'] }}</p>
            <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-white">{{ $f['heading'] }}</h2>
            <p class="mt-4 text-slate-400">
                {{ $f['subhead'] }}
                {{ $f['contact_text'] }}
                <a href="mailto:{{ $contactEmail }}" class="text-indigo-400 hover:text-indigo-300 underline underline-offset-2 transition-colors">{{ $f['contact_link'] }}</a>
            </p>
        </div>

        <dl x-data="{ open: null }" class="space-y-3" data-reveal data-reveal-delay="2">
            @foreach($faqs as $i => $faq)
                <div
                    class="rounded-2xl overflow-hidden transition-all duration-200"
                    style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);"
                    :style="open === {{ $i }} ? 'border-color: rgba(99,102,241,0.35); background: rgba(99,102,241,0.06);' : ''"
                >
                    <dt>
                        <button
                            type="button"
                            class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-inset rounded-2xl"
                            @click="open = open === {{ $i }} ? null : {{ $i }}"
                            :aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                            aria-controls="faq-answer-{{ $i }}"
                            id="faq-btn-{{ $i }}"
                        >
                            <span class="font-semibold text-white text-[0.95rem] leading-snug">{{ $faq['q'] }}</span>

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

                    <dd
                        id="faq-answer-{{ $i }}"
                        role="region"
                        :aria-labelledby="'faq-btn-{{ $i }}'"
                        class="overflow-hidden transition-all duration-300 ease-in-out"
                        :style="open === {{ $i }} ? 'max-height: 400px; opacity: 1;' : 'max-height: 0; opacity: 0;'"
                        style="max-height: 0; opacity: 0;"
                    >
                        <p class="px-6 pb-6 text-slate-400 leading-relaxed text-sm">{{ $faq['a'] }}</p>
                    </dd>
                </div>
            @endforeach
        </dl>

    </div>
</section>
