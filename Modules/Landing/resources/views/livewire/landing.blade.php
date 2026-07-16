<div>

    {{-- Navbar: fixed glassmorphism header, shared across all sections --}}
    <x-landing::navbar />

    {{-- ══════════════════════════════════════════════════════════
         SECTION 1: HERO
         Full-viewport opening section — product name, tagline,
         CTA buttons, reveal animations, ambient background glow.
         Implemented in: Task 23
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.hero')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 2: FEATURES
         Glassmorphism cards, scroll-reveal per card,
         hover glow/scale effects, 4–6 GIF-related features.
         Implemented in: Task 24
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.features')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 3: WHY CHOOSE US
         Two-column layout (text + visual), stagger animation,
         parallax background layer.
         Implemented in: Task 25
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.why-us')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 4: SHOWCASE
         Product mockup grid / carousel, hover overlay,
         smooth transition into next section.
         Implemented in: Task 26
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.showcase')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 5: STATISTICS
         Counter animation (0 → final value on scroll entry),
         large typography, minimal design.
         Implemented in: Task 27
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.statistics')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 6: TESTIMONIALS  (Task 28)
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.testimonials')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 7: FAQ  (Task 28)
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.faq')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 8: FINAL CTA
         Distinct from Hero, ripple-effect button,
         strong conversion copy.
         Implemented in: Task 29
    ════════════════════════════════════════════════════════════ --}}
    <section id="cta" aria-label="Get Started" class="relative py-32 overflow-hidden">
        {{-- Task 29 placeholder --}}
        <div class="text-center text-slate-500 py-16">Final CTA section — coming in Task 29</div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 9: FOOTER
         Navigation links, login/register links,
         minimal clean design.
         Implemented in: Task 29
    ════════════════════════════════════════════════════════════ --}}
    <footer id="footer" aria-label="Site footer" class="relative overflow-hidden">
        {{-- Task 29 placeholder --}}
        <div class="text-center text-slate-500 py-16">Footer — coming in Task 29</div>
    </footer>

</div>
