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
    @include('landing::sections.showcase', ['showcaseGifs' => $showcaseGifs])

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
         SECTION 8: FINAL CTA  (Task 29)
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.cta')

    {{-- ══════════════════════════════════════════════════════════
         SECTION 9: FOOTER  (Task 29)
    ════════════════════════════════════════════════════════════ --}}
    @include('landing::sections.footer')

</div>
