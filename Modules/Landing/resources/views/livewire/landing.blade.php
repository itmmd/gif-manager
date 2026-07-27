<div>
    <x-landing::navbar />

    @include('landing::sections.hero')
    @include('landing::sections.features')
    @include('landing::sections.why-us')
    @include('landing::sections.showcase', ['showcaseGifs' => $showcaseGifs])
    @include('landing::sections.statistics')
    @include('landing::sections.testimonials')
    @include('landing::sections.faq')
    @include('landing::sections.cta')
    @include('landing::sections.footer')
</div>
