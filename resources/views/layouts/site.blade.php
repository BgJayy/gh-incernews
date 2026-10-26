<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Incer News')</title>
        @unless (config('app.indexing'))
            <meta name="robots" content="noindex, nofollow">
        @endunless
        <meta name="description" content="@yield('description', 'Indonesia Cerdas: Menyongsong Era Globalisasi')">
        <link rel="canonical" href="@yield('canonical', url()->current())">
        <meta property="og:site_name" content="Incer News">
        <meta property="og:title" content="@yield('title', 'Incer News')">
        <meta property="og:description" content="@yield('description', 'Indonesia Cerdas: Menyongsong Era Globalisasi')">
        <meta property="og:url" content="@yield('canonical', url()->current())">
        <meta property="og:type" content="@yield('og_type', 'website')">
        @php($ogImage = trim($__env->yieldContent('og_image')) ?: asset('images/logo-incernews.png'))
        <meta name="twitter:card" content="{{ trim($__env->yieldContent('og_image')) !== '' ? 'summary_large_image' : 'summary' }}">
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:image:alt" content="@yield('og_image_alt', 'Incer News')">
        <link rel="icon" href="{{ asset('images/logo-incernews.png') }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('images/logo-incernews.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen bg-surface font-sans text-on-surface antialiased">
        <header class="sticky top-0 z-50 bg-surface-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
            <div class="mx-auto flex max-w-[1400px] flex-col justify-between px-4 py-2 md:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-6">
                    <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
                        <img src="{{ asset('images/logo-incernews.png') }}" alt="Incer News" width="40" height="40" class="h-10 w-10 object-contain">
                        <span>
                            <span class="block text-lg font-extrabold uppercase tracking-tight text-on-surface">IncerNews</span>
                            <span class="mt-0.5 hidden text-[11px] text-on-surface-variant sm:block">Indonesia Cerdas</span>
                        </span>
                    </a>

                    <nav class="hidden items-center gap-6 lg:flex" aria-label="Kanal utama">
                        <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-secondary underline decoration-2 underline-offset-8' : 'text-on-surface-variant hover:text-on-surface' }}">Beranda</a>
                        @foreach ($navCategories as $item)
                            <a href="{{ route('categories.show', $item->slug) }}" class="text-sm font-semibold {{ request()->is('kategori/'.$item->slug) ? 'text-secondary underline decoration-2 underline-offset-8' : 'text-on-surface-variant hover:text-on-surface' }}">{{ $item->name }}</a>
                        @endforeach
                    </nav>

                    <div class="flex items-center gap-3">
                        <p class="hidden text-right xl:block">
                            <span class="block text-xs text-on-surface-variant">{{ now()->translatedFormat('l, d F Y') }}</span>
                        </p>
                        <form action="{{ route('search') }}" method="get" class="hidden sm:block">
                            <label class="sr-only" for="q">Cari berita</label>
                            <input id="q" name="q" value="{{ request('q') }}" type="search" placeholder="Cari berita" class="h-10 w-48 rounded border border-outline-variant bg-surface-low px-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary lg:w-56">
                        </form>
                        <a href="{{ route('search') }}" class="flex h-10 w-10 items-center justify-center text-on-surface-variant hover:text-on-surface sm:hidden" aria-label="Pencarian berita">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        </a>
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto pb-2 lg:hidden" aria-label="Kanal">
                    <a href="{{ route('home') }}" class="shrink-0 rounded-full px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider {{ request()->routeIs('home') ? 'bg-primary text-white' : 'bg-surface-low text-on-surface-variant' }}">Beranda</a>
                    @foreach ($navCategories as $item)
                        <a href="{{ route('categories.show', $item->slug) }}" class="shrink-0 rounded-full px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider {{ request()->is('kategori/'.$item->slug) ? 'bg-primary text-white' : 'bg-surface-low text-on-surface-variant' }}">{{ $item->name }}</a>
                    @endforeach
                </nav>
            </div>

            @if ($breakingArticle)
                <div class="border-t border-surface-low bg-surface-low">
                    <div class="mx-auto flex max-w-[1400px] items-center gap-3 px-4 py-2 md:px-6 lg:px-8">
                        <span class="shrink-0 rounded bg-tertiary px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-white">Terbaru</span>
                        <a href="{{ route('articles.show', $breakingArticle->slug) }}" class="truncate text-xs font-medium text-on-surface hover:text-secondary">{{ $breakingArticle->title }}</a>
                        @if ($breakingArticle->published_at)
                            <span class="hidden shrink-0 text-xs text-outline md:inline">{{ $breakingArticle->published_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            @endif

            @hasSection('progress')
                <div class="h-1 bg-surface-low">
                    <div data-read-progress class="h-full w-0 bg-primary transition-[width] duration-75"></div>
                </div>
            @endif
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="mt-16 bg-secondary text-white">
            <div class="mx-auto grid max-w-[1400px] gap-8 px-4 py-12 md:grid-cols-12 md:px-6 lg:px-8">
                <div class="md:col-span-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-incernews.png') }}" alt="Incer News" width="36" height="36" class="h-9 w-9 object-contain">
                        <p class="text-lg font-extrabold uppercase tracking-tight">IncerNews</p>
                    </div>
                    <p class="mt-3 max-w-sm text-sm text-secondary-fixed">Media digital independen untuk kabar nasional, daerah, opini, dan liputan mendalam.</p>
                </div>
                <div class="md:col-span-6">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-secondary-fixed">Kanal</p>
                    <div class="mt-3 flex flex-col gap-2">
                        @foreach ($navCategories as $item)
                            <a href="{{ route('categories.show', $item->slug) }}" class="text-sm text-white/80 hover:text-white">{{ $item->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10">
                <div class="mx-auto max-w-[1400px] px-4 py-4 text-xs text-secondary-fixed md:px-6 lg:px-8">
                    © {{ now()->year }} Incer News. Indonesia Cerdas: Menyongsong Era Globalisasi.
                </div>
            </div>
        </footer>
    </body>
</html>
