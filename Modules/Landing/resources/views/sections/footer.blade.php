{{--
    Footer Section — Task 29
    ────────────────────────────────────────────────────────────
    • Four-column grid on desktop, stacked on mobile
    • Logo + tagline, nav columns, social links
    • Login / Register CTA links
    • Bottom bar: copyright + legal links
    • Clean, minimal — matches dark premium theme
    ────────────────────────────────────────────────────────────
--}}
<footer
    id="footer"
    aria-label="Site footer"
    class="relative overflow-hidden"
    style="background: #07070d; border-top: 1px solid rgba(255,255,255,0.06);"
>
    {{-- subtle top glow --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-px"
         style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.25), transparent);"></div>

    <div class="max-w-6xl mx-auto px-6 pt-16 pb-10">

        {{-- Main grid --}}
        <div class="grid grid-cols-2 gap-10 sm:grid-cols-2 lg:grid-cols-4 mb-14">

            {{-- Col 1: Brand --}}
            <div class="col-span-2 sm:col-span-2 lg:col-span-1">
                <a href="/" class="inline-flex items-center gap-2.5 mb-4" aria-label="GIF Manager home">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="white" stroke-width="2.5" aria-hidden="true">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-white text-base tracking-tight">GIF Manager</span>
                </a>
                <p class="text-slate-500 text-sm leading-relaxed max-w-[220px] mb-6">
                    The fastest way to organise, search, and share your GIF collection.
                </p>
                {{-- Social links --}}
                <div class="flex gap-3">
                    @foreach([
                        ['GitHub', 'https://github.com', 'M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z'],
                        ['Twitter / X', 'https://twitter.com', 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.742l7.736-8.852L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
                        ['Discord', 'https://discord.com', 'M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 00-5.487 0 12.64 12.64 0 00-.617-1.25.077.077 0 00-.079-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 00.031.057 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028 14.09 14.09 0 001.226-1.994.076.076 0 00-.041-.106 13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128 10.2 10.2 0 00.372-.292.074.074 0 01.077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 01.078.01c.12.098.246.198.373.292a.077.077 0 01-.006.127 12.299 12.299 0 01-1.873.892.077.077 0 00-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.028 19.839 19.839 0 006.002-3.03.077.077 0 00.032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.03z'],
                    ] as [$label, $href, $path])
                    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
                       aria-label="{{ $label }}"
                       class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-white transition-all duration-200"
                       style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);"
                       onmouseover="this.style.background='rgba(99,102,241,0.2)'; this.style.borderColor='rgba(99,102,241,0.4)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.08)'"
                    >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="{{ $path }}"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Col 2: Product --}}
            <nav aria-label="Product links">
                <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 mb-4">Product</p>
                <ul class="space-y-3">
                    @foreach([
                        ['Features',    '#features'],
                        ['Showcase',    '#showcase'],
                        ['Pricing',     '#cta'],
                        ['Changelog',   '#'],
                        ['Roadmap',     '#'],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}"
                           class="text-sm text-slate-400 hover:text-white transition-colors">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </nav>

            {{-- Col 3: Account --}}
            <nav aria-label="Account links">
                <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 mb-4">Account</p>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('register') }}"
                           class="text-sm text-slate-400 hover:text-white transition-colors">
                            Create account
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}"
                           class="text-sm text-slate-400 hover:text-white transition-colors">
                            Sign in
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-slate-400 hover:text-white transition-colors">
                            Forgot password
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-sm text-slate-400 hover:text-white transition-colors">
                            Dashboard
                        </a>
                    </li>
                    @endauth
                </ul>
            </nav>

            {{-- Col 4: Company --}}
            <nav aria-label="Company links">
                <p class="text-xs font-semibold tracking-widest uppercase text-slate-500 mb-4">Company</p>
                <ul class="space-y-3">
                    @foreach([
                        ['About',        '#'],
                        ['Blog',         '#'],
                        ['Contact',      'mailto:hello@gifmanager.app'],
                        ['Privacy',      '#'],
                        ['Terms',        '#'],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}"
                           class="text-sm text-slate-400 hover:text-white transition-colors">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </nav>

        </div>

        {{-- Bottom bar --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8"
             style="border-top:1px solid rgba(255,255,255,0.06);">

            <p class="text-xs text-slate-600">
                &copy; {{ date('Y') }} GIF Manager. All rights reserved.
            </p>

            <div class="flex items-center gap-5">
                <a href="#" class="text-xs text-slate-600 hover:text-slate-400 transition-colors">Privacy Policy</a>
                <a href="#" class="text-xs text-slate-600 hover:text-slate-400 transition-colors">Terms of Service</a>
                <a href="#" class="text-xs text-slate-600 hover:text-slate-400 transition-colors">Cookie Policy</a>
            </div>

        </div>
    </div>
</footer>
