{{--
    Final CTA Section — Task 29
    ────────────────────────────────────────────────────────────
    • Distinct from Hero: full-width gradient card with noise texture
    • Ripple effect on primary button (Alpine.js + CSS @keyframes)
    • Strong conversion copy, secondary link to login
    • scroll-reveal on entry
    ────────────────────────────────────────────────────────────
--}}
<section
    id="cta"
    aria-label="Get Started"
    class="relative py-24 px-6 overflow-hidden"
>
    {{-- top separator --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-px"
         style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.35), transparent);"></div>

    <div class="relative max-w-4xl mx-auto" style="z-index:1;">
        <div
            data-reveal
            class="relative rounded-3xl overflow-hidden text-center px-8 py-20 sm:px-16"
            style="
                background: linear-gradient(135deg,
                    rgba(99,102,241,0.18) 0%,
                    rgba(139,92,246,0.14) 50%,
                    rgba(6,182,212,0.10) 100%);
                border: 1px solid rgba(99,102,241,0.25);
            "
        >
            {{-- Noise texture overlay --}}
            <div aria-hidden="true" class="pointer-events-none absolute inset-0"
                 style="
                     background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E&quot;);
                     opacity: 0.4;
                 "></div>

            {{-- Glow orbs inside card --}}
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
                <div style="position:absolute; top:-30%; left:-10%;
                            width:60%; height:80%;
                            background:radial-gradient(circle, rgba(99,102,241,0.20) 0%, transparent 65%);
                            filter:blur(60px);"></div>
                <div style="position:absolute; bottom:-20%; right:-5%;
                            width:50%; height:70%;
                            background:radial-gradient(circle, rgba(6,182,212,0.15) 0%, transparent 65%);
                            filter:blur(60px);"></div>
            </div>

            {{-- Content --}}
            <div class="relative" style="z-index:2;">

                {{-- Eyebrow --}}
                <div class="inline-flex items-center gap-2 rounded-full text-xs font-semibold tracking-widest uppercase mb-6"
                     style="background:rgba(99,102,241,0.15);
                            border:1px solid rgba(99,102,241,0.30);
                            padding:0.35rem 1rem;
                            color:#a5b4fc;">
                    Start for free — no credit card needed
                </div>

                {{-- Headline --}}
                <h2 class="font-black tracking-tight text-white mb-5"
                    style="font-size:clamp(2rem,6vw,3.5rem); line-height:1.08; letter-spacing:-0.03em;">
                    Your GIF collection<br>
                    <span style="
                        background: linear-gradient(130deg,#a5b4fc,#818cf8,#c084fc,#67e8f9);
                        -webkit-background-clip:text;
                        -webkit-text-fill-color:transparent;
                        background-clip:text;
                    ">deserves better.</span>
                </h2>

                {{-- Sub-copy --}}
                <p class="text-slate-400 leading-relaxed max-w-lg mx-auto mb-10"
                   style="font-size:clamp(1rem,2vw,1.15rem);">
                    Join 5,000+ creators who stopped losing their best GIFs in endless folders.
                    Set up in under 2 minutes.
                </p>

                {{-- Ripple CTA button --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">

                    <div
                        x-data="{
                            ripples: [],
                            addRipple(e) {
                                const rect = $el.getBoundingClientRect();
                                const x = e.clientX - rect.left;
                                const y = e.clientY - rect.top;
                                const id = Date.now();
                                this.ripples.push({ id, x, y });
                                setTimeout(() => {
                                    this.ripples = this.ripples.filter(r => r.id !== id);
                                }, 700);
                            }
                        }"
                        class="relative overflow-hidden rounded-2xl"
                        @click="addRipple($event)"
                    >
                        <a
                            href="{{ route('register') }}"
                            class="relative inline-flex items-center gap-2.5 text-white font-bold rounded-2xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-indigo-400"
                            style="
                                background: linear-gradient(135deg,#6366f1,#8b5cf6,#06b6d4);
                                padding: 1rem 2.4rem;
                                font-size: 1.05rem;
                                box-shadow: 0 0 40px rgba(99,102,241,0.55), 0 4px 24px rgba(99,102,241,0.35);
                                transition: box-shadow 0.3s ease, transform 0.2s ease;
                            "
                            onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 0 56px rgba(99,102,241,0.75), 0 8px 32px rgba(99,102,241,0.45)'"
                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 0 40px rgba(99,102,241,0.55), 0 4px 24px rgba(99,102,241,0.35)'"
                        >
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Create Free Account
                        </a>

                        {{-- Ripple circles --}}
                        <template x-for="r in ripples" :key="r.id">
                            <span
                                class="ripple-circle pointer-events-none absolute rounded-full"
                                :style="`left:${r.x}px; top:${r.y}px;`"
                                aria-hidden="true"
                            ></span>
                        </template>
                    </div>

                    <a
                        href="{{ route('login') }}"
                        class="text-sm text-slate-400 hover:text-slate-200 transition-colors"
                    >
                        Already have an account? <span class="underline underline-offset-2">Sign in →</span>
                    </a>

                </div>

                {{-- Trust signals --}}
                <div class="mt-10 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                        Free forever plan
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                        No credit card required
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                        Cancel anytime
                    </span>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Ripple keyframe --}}
<style>
    .ripple-circle {
        width: 10px;
        height: 10px;
        margin-left: -5px;
        margin-top: -5px;
        background: rgba(255,255,255,0.35);
        animation: ripple-expand 0.7s ease-out forwards;
    }
    @keyframes ripple-expand {
        0%   { transform: scale(0);   opacity: 0.6; }
        100% { transform: scale(28);  opacity: 0;   }
    }
</style>
