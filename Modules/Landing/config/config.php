<?php

return [

    'name' => 'Landing',

    // ---------------------------------------------------------------------------
    // Brand & contact
    // ---------------------------------------------------------------------------
    'brand' => [
        'name'          => 'GIF Manager',
        'tagline'       => 'The fastest way to organise, search, and share your GIF collection.',
        'contact_email' => 'hello@gifmanager.app',
    ],

    // ---------------------------------------------------------------------------
    // SEO defaults — overridable per-page via $title / $description slot vars
    // ---------------------------------------------------------------------------
    'seo' => [
        'title'       => 'GIF Manager — Organize, Share & Discover GIFs',
        'description' => 'The fastest way to upload, organize, and share your GIF collection. Smart search, instant categories, one-click sharing.',
        'keywords'    => 'gif manager, gif organizer, upload gif, share gif, gif collection',
        'og_type'     => 'website',
        'robots'      => 'index, follow',
        'og_image'    => ['width' => 1200, 'height' => 630],
    ],

    // ---------------------------------------------------------------------------
    // Anchor nav (desktop center + mobile panel)
    // ---------------------------------------------------------------------------
    'nav' => [
        ['href' => '#features',   'label' => 'Features'],
        ['href' => '#why-us',     'label' => 'Why Us'],
        ['href' => '#showcase',   'label' => 'Showcase'],
        ['href' => '#statistics', 'label' => 'Stats'],
        ['href' => '#faq',        'label' => 'FAQ'],
    ],

    // ---------------------------------------------------------------------------
    // Showcase — number of GIFs pulled from the gallery, plus placeholder cards
    // shown when the library is empty.
    // ---------------------------------------------------------------------------
    'showcase' => [
        'count'           => 8,
        'view_all_label'  => 'View all GIFs',
        'placeholders'    => [
            ['label' => 'Gaming Reactions',  'gradient' => 'from-indigo-600/50 to-violet-700/40'],
            ['label' => 'Team Memes',        'gradient' => 'from-violet-600/50 to-fuchsia-600/40'],
            ['label' => 'Tech Demos',        'gradient' => 'from-cyan-600/50 to-blue-700/40'],
            ['label' => 'Celebrations',      'gradient' => 'from-pink-600/50 to-rose-600/40'],
            ['label' => 'Nature & Travel',   'gradient' => 'from-emerald-600/50 to-teal-700/40'],
            ['label' => 'UI Animations',     'gradient' => 'from-amber-600/50 to-orange-700/40'],
            ['label' => 'Product Mockups',   'gradient' => 'from-indigo-600/50 to-blue-600/40'],
            ['label' => 'Tutorial Steps',    'gradient' => 'from-violet-600/50 to-purple-700/40'],
        ],
    ],

    // ---------------------------------------------------------------------------
    // Hero
    // ---------------------------------------------------------------------------
    'hero' => [
        'announcement' => 'Now in private beta — join the first 500 members',
        'headline'     => 'Organize, share &amp; discover',
        'headline_em'  => 'GIFs',
        'headline_tail'=> 'like never before',
        'subtitle'     => 'The fastest way to upload, tag, and share your GIF collection. Smart search, instant categories, and one-click sharing — all in one beautifully simple workspace.',
        'cta_primary'  => 'Start free — no credit card',
        'cta_secondary'=> 'See how it works',
        'trust_label'  => 'Trusted by teams at',
        'trust_logos'  => ['Acme Studio', 'Pixelcraft', 'MotionLab', 'Devhouse'],
    ],

    // ---------------------------------------------------------------------------
    // Features — icon slug maps to the SVG path kept in features.blade.php
    // ---------------------------------------------------------------------------
    'features' => [
        'eyebrow'  => 'Features',
        'heading'  => 'Everything you need to',
        'heading_em' => 'manage your GIFs',
        'subhead'  => 'Powerful tools wrapped in a simple, beautiful interface — built for creators, teams, and anyone who lives in motion.',
        'items'    => [
            ['title' => 'Lightning Upload',  'description' => 'Drag-and-drop or paste a URL. GIFs are processed and ready in seconds — no compression guesswork.', 'icon' => 'upload',     'color' => 'indigo'],
            ['title' => 'Smart Search',      'description' => 'Find any GIF instantly with intelligent tagging, fuzzy matching, and saved searches.',              'icon' => 'search',     'color' => 'violet'],
            ['title' => 'Auto Categories',   'description' => 'GIFs are sorted into categories automatically, so your library stays organized as it grows.',        'icon' => 'folder',     'color' => 'cyan'],
            ['title' => 'One-Click Share',   'description' => 'Generate shareable links or embed codes instantly. Push to Slack, Discord, or anywhere.',            'icon' => 'share',      'color' => 'indigo'],
            ['title' => 'Collections',       'description' => 'Bundle related GIFs into curated collections for projects, moods, or campaigns.',                      'icon' => 'collection', 'color' => 'violet'],
            ['title' => 'Blazing CDN',       'description' => 'Every GIF is served from a global edge network for instant playback, anywhere on earth.',              'icon' => 'bolt',       'color' => 'cyan'],
        ],
    ],

    // ---------------------------------------------------------------------------
    // Why Us
    // ---------------------------------------------------------------------------
    'why_us' => [
        'eyebrow'  => 'Why Choose Us',
        'heading'  => 'Built for people who',
        'heading_em' => 'move fast',
        'intro'    => 'Most GIF tools feel like a cluttered file manager. We rebuilt the experience from scratch — fast, focused, and beautiful.',
        'benefits' => [
            ['title' => 'Designed for speed',           'desc' => 'Every interaction is optimized — from upload to search, nothing takes more than a click.'],
            ['title' => 'Privacy first',                'desc' => 'Your library is yours. No public exposure unless you choose to share.'],
            ['title' => 'Works on every device',        'desc' => 'Fully responsive. Manage your GIFs on desktop, tablet, or phone without compromise.'],
            ['title' => 'Built by creators, for creators', 'desc' => 'We use GIF Manager every day. What you see is shaped by real workflows.'],
        ],
    ],

    // ---------------------------------------------------------------------------
    // Statistics — counters animate 0 → value on scroll-in
    // ---------------------------------------------------------------------------
    'stats' => [
        'eyebrow' => 'Stats',
        'heading' => 'Trusted by',
        'heading_em' => 'thousands',
        'items'   => [
            ['value' => 10000, 'suffix' => '+', 'label' => 'GIFs Uploaded', 'duration' => 2000],
            ['value' => 5000,  'suffix' => '+', 'label' => 'Active Users',  'duration' => 1800],
            ['value' => 120,   'suffix' => '+', 'label' => 'Categories',    'duration' => 1400],
            ['value' => 99.9,  'suffix' => '%', 'label' => 'Uptime',        'duration' => 1600],
        ],
    ],

    // ---------------------------------------------------------------------------
    // Testimonials
    // ---------------------------------------------------------------------------
    'testimonials' => [
        'eyebrow' => 'What people say',
        'heading' => 'Loved by creators',
        'subhead' => 'Thousands of people use GIF Manager every day to organise and share their collections.',
        'items'   => [
            ['name' => 'Sarah Kim',    'role' => 'Content Creator',       'avatar' => 'SK', 'color' => '#6366f1', 'stars' => 5, 'quote' => 'Finally a tool that actually keeps my GIF library tidy. The smart search alone saves me 20 minutes a day.'],
            ['name' => 'Marcus Reid',  'role' => 'UI Designer',           'avatar' => 'MR', 'color' => '#8b5cf6', 'stars' => 5, 'quote' => 'The auto-categorize feature is genuinely magic. I dumped 3,000 GIFs in and it sorted them perfectly.'],
            ['name' => 'Priya Nair',   'role' => 'Social Media Manager',  'avatar' => 'PN', 'color' => '#06b6d4', 'stars' => 5, 'quote' => 'One-click sharing to any platform changed how I work. My engagement went up 40% since I started using it.'],
            ['name' => 'Tom Erikson',  'role' => 'Indie Developer',       'avatar' => 'TE', 'color' => '#f59e0b', 'stars' => 5, 'quote' => 'Clean, fast, and zero bloat. This is exactly what a GIF manager should be — nothing more, nothing less.'],
            ['name' => 'Lena Schulz',  'role' => 'Motion Designer',       'avatar' => 'LS', 'color' => '#10b981', 'stars' => 5, 'quote' => 'I\'ve tried every GIF manager out there. This is the only one that doesn\'t get in my way.'],
        ],
    ],

    // ---------------------------------------------------------------------------
    // FAQ
    // ---------------------------------------------------------------------------
    'faqs' => [
        'eyebrow'  => 'Got questions?',
        'heading'  => 'Frequently asked',
        'subhead'  => 'Everything you need to know about GIF Manager.',
        'contact_text' => "Can't find the answer?",
        'contact_link' => 'Send us a message.',
        'items'    => [
            ['q' => 'Is GIF Manager free to use?',                        'a' => 'Yes — the core features (upload, organise, search, share) are completely free. We offer a Pro plan for power users who need unlimited storage, advanced analytics, and team collaboration features.'],
            ['q' => 'How does the auto-categorize feature work?',         'a' => "When you upload a GIF, our system analyses the content and automatically assigns relevant tags and categories. You can always edit or override these — it's a starting point, not a cage."],
            ['q' => 'What file formats are supported?',                   'a' => 'We support GIF, WebP (animated), APNG, and MP4 converted to GIF. Maximum file size is 50 MB on the free plan and 200 MB on Pro.'],
            ['q' => "Can I share GIFs with people who don't have an account?", 'a' => 'Absolutely. Every GIF gets a unique shareable link. Recipients can view and download without signing up. You can also set links to expire or be password-protected.'],
            ['q' => 'Is my content private by default?',                  'a' => 'Yes. All uploads are private by default. You explicitly choose what to make public, share via link, or keep entirely private. We never surface your content to other users without your permission.'],
            ['q' => 'Can I import my existing GIF collection?',           'a' => 'Yes — you can bulk-upload a folder of GIFs, import from a ZIP archive, or connect to Giphy and Tenor to migrate your saved collections.'],
        ],
    ],

    // ---------------------------------------------------------------------------
    // Final CTA
    // ---------------------------------------------------------------------------
    'cta' => [
        'eyebrow'    => 'Start for free — no credit card needed',
        'headline'   => 'Your GIF collection',
        'headline_em'=> 'deserves better.',
        'subhead'    => 'Join 5,000+ creators who stopped losing their best GIFs in endless folders. Set up in under 2 minutes.',
        'primary'    => 'Create Free Account',
        'secondary_pre' => 'Already have an account?',
        'secondary'  => 'Sign in',
        'trust_signals' => ['Free forever plan', 'No credit card required', 'Cancel anytime'],
    ],

    // ---------------------------------------------------------------------------
    // Social links — icon slug maps to the SVG path kept in footer.blade.php
    // ---------------------------------------------------------------------------
    'social' => [
        ['label' => 'GitHub',      'url' => 'https://github.com',   'icon' => 'github'],
        ['label' => 'Twitter / X', 'url' => 'https://twitter.com',  'icon' => 'twitter'],
        ['label' => 'Discord',     'url' => 'https://discord.com',  'icon' => 'discord'],
    ],

    // ---------------------------------------------------------------------------
    // Footer columns
    // ---------------------------------------------------------------------------
    'footer' => [
        'product' => [
            ['label' => 'Features',  'href' => '#features'],
            ['label' => 'Showcase',  'href' => '#showcase'],
            ['label' => 'Pricing',   'href' => '#cta'],
            ['label' => 'Changelog', 'href' => '#'],
            ['label' => 'Roadmap',   'href' => '#'],
        ],
        // 'Account' column is built from named routes in footer.blade.php
        'company' => [
            ['label' => 'About',   'href' => '#'],
            ['label' => 'Blog',    'href' => '#'],
            ['label' => 'Contact', 'href' => 'mailto:hello@gifmanager.app'],
            ['label' => 'Privacy', 'href' => '#'],
            ['label' => 'Terms',   'href' => '#'],
        ],
        'legal' => ['Privacy Policy', 'Terms of Service', 'Cookie Policy'],
        'column_labels' => ['Product', 'Account', 'Company'],
        'copyright' => 'GIF Manager. All rights reserved.',
    ],

];
