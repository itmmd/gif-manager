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
    <section id="why-us" aria-label="Why Choose Us" class="relative py-32 overflow-hidden">
        {{-- Task 25 placeholder --}}
        <div class="text-center text-slate-500 py-16">Why Choose Us section — coming in Task 25</div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 4: SHOWCASE
         Product mockup grid / carousel, hover overlay,
         smooth transition into next section.
         Implemented in: Task 26
    ════════════════════════════════════════════════════════════ --}}
    <section id="showcase" aria-label="Showcase" class="relative py-32 overflow-hidden">
        {{-- Task 26 placeholder --}}
        <div class="text-center text-slate-500 py-16">Showcase section — coming in Task 26</div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 5: STATISTICS
         Counter animation (0 → final value on scroll entry),
         large typography, minimal design.
         Implemented in: Task 27
    ════════════════════════════════════════════════════════════ --}}
    <section id="statistics" aria-label="Statistics" class="relative py-32 overflow-hidden">
        {{-- Task 27 placeholder --}}
        <div class="text-center text-slate-500 py-16">Statistics section — coming in Task 27</div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 6: TESTIMONIALS
         User quote cards, horizontal scroll / carousel,
         smooth scroll interaction.
         Implemented in: Task 28
    ════════════════════════════════════════════════════════════ --}}
    <section id="testimonials" aria-label="Testimonials" class="relative py-32 overflow-hidden">
        {{-- Task 28 placeholder --}}
        <div class="text-center text-slate-500 py-16">Testimonials section — coming in Task 28</div>
    </section>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 7: FAQ
         Alpine.js accordion, animated height/opacity,
         keyboard-accessible.
         Implemented in: Task 28
    ════════════════════════════════════════════════════════════ --}}
    <section id="faq" aria-label="Frequently Asked Questions" class="relative py-32 overflow-hidden">
        {{-- Task 28 placeholder --}}
        <div class="text-center text-slate-500 py-16">FAQ section — coming in Task 28</div>
    </section>

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
