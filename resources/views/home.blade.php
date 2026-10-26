@extends('layouts.site')

@section('title', 'Incer News — Indonesia Cerdas')
@section('description', 'Berita nasional, daerah, opini, dan liputan mendalam dari Incer News.')
@section('canonical', route('home'))

@section('content')
    <div class="mx-auto max-w-[1400px] px-4 py-6 md:px-6 lg:px-8">
        @if ($hero || $trending->isNotEmpty())
            <section aria-label="Headline utama dan tren" class="grid items-stretch gap-6 lg:grid-cols-12">
                @if ($hero)
                <article class="flex flex-col overflow-hidden rounded-xl bg-surface-lowest shadow-sm {{ $trending->isNotEmpty() ? 'lg:col-span-7' : 'lg:col-span-12' }}">
                    <a href="{{ route('articles.show', $hero->slug) }}" class="relative block h-[360px] overflow-hidden bg-surface-low md:h-[440px]">
                        @if ($hero->image_url)
                            <img src="{{ $hero->image_url }}" alt="{{ $hero->imageAlt() }}" width="960" height="600" fetchpriority="high" class="h-full w-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/20 to-transparent"></div>
                        <span class="absolute top-4 left-4 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $hero->category->badgeClass() }}">{{ $hero->category->name }}</span>
                        <div class="absolute right-4 bottom-4 left-4 text-white">
                            <p class="mb-1 text-xs font-semibold tracking-widest text-secondary-fixed uppercase">Fokus utama</p>
                            <h1 class="text-2xl leading-tight font-extrabold tracking-tight md:text-4xl">{{ $hero->title }}</h1>
                        </div>
                    </a>
                    <div class="flex flex-1 flex-col justify-between p-6">
                        <p class="line-clamp-3 text-lg leading-8 text-on-surface-variant">{{ $hero->excerpt }}</p>
                        <div class="mt-6 flex items-center justify-between border-t border-surface-low pt-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-container text-xs font-bold text-white">{{ $hero->author->initials() }}</span>
                                <div>
                                    <p class="text-sm font-semibold">{{ $hero->author->name }}</p>
                                    <p class="text-xs text-outline">{{ $hero->publishedLabel() }} · {{ $hero->readingMinutes() }} min baca</p>
                                </div>
                            </div>
                            <a href="{{ route('articles.show', $hero->slug) }}" class="text-sm font-semibold text-primary hover:underline">Baca lengkap</a>
                        </div>
                    </div>
                </article>
                @endif

                @if ($trending->isNotEmpty())
                    <aside class="flex flex-col rounded-xl bg-surface-lowest p-4 shadow-sm {{ $hero ? 'lg:col-span-5' : 'lg:col-span-12' }}">
                        <div class="mb-3 flex items-center justify-between border-b border-surface-low pb-3">
                            <h2 class="text-lg font-bold tracking-tight uppercase">Tren &amp; terpopuler</h2>
                        </div>
                        <div class="flex flex-col divide-y divide-surface-low">
                            @foreach ($trending as $article)
                                <article class="flex items-start gap-4 py-3">
                                    <span class="w-8 shrink-0 pt-1 text-3xl font-black text-outline-variant">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="min-w-0 flex-1">
                                        <div class="mb-1 flex items-center gap-2">
                                            <span class="rounded px-2 py-0.5 text-[11px] font-bold uppercase {{ $article->category->badgeClass() }}">{{ $article->category->name }}</span>
                                            <span class="text-xs text-outline">{{ $article->published_at?->diffForHumans() }}</span>
                                        </div>
                                        <h3 class="text-base leading-snug font-semibold hover:text-primary">
                                            <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                        </h3>
                                    </div>
                                    <a href="{{ route('articles.show', $article->slug) }}" class="h-20 w-20 shrink-0 overflow-hidden rounded bg-surface-low">
                                        @if ($article->image_url)
                                            <img src="{{ $article->image_url }}" alt="{{ $article->imageAlt() }}" width="160" height="160" loading="lazy" class="h-full w-full object-cover">
                                        @endif
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </aside>
                @endif
            </section>
        @endif

        @foreach ($blocks as $category)
            <section class="mt-12 {{ $category->slug === 'liputan-khusus' ? 'rounded-2xl bg-inverse p-6 text-inverse-on md:p-10' : '' }}" aria-labelledby="kanal-{{ $category->slug }}">
                <div class="mb-5 flex items-end justify-between">
                    <div class="flex items-center gap-3">
                        <span class="h-7 w-3 rounded-sm {{ $category->slug === 'liputan-khusus' ? 'bg-tertiary' : $category->accentClass() }}"></span>
                        <h2 id="kanal-{{ $category->slug }}" class="text-2xl font-bold tracking-tight uppercase {{ $category->slug === 'liputan-khusus' ? 'text-white' : 'text-on-surface' }}">{{ $category->name }}</h2>
                    </div>
                    <a href="{{ route('categories.show', $category->slug) }}" class="text-sm font-bold hover:underline {{ $category->slug === 'liputan-khusus' ? 'text-secondary-fixed' : $category->linkClass() }}">Lihat semua</a>
                </div>

                @if ($category->slug === 'liputan-khusus')
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach ($category->homeArticles as $article)
                            <article class="overflow-hidden rounded-xl bg-on-surface/40">
                                <a href="{{ route('articles.show', $article->slug) }}" class="relative block h-64 overflow-hidden bg-black/40">
                                    @if ($article->image_url)
                                        <img src="{{ $article->image_url }}" alt="{{ $article->imageAlt() }}" width="800" height="500" loading="lazy" class="h-full w-full object-cover">
                                    @endif
                                </a>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-white">
                                        <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-primary-fixed">{{ $article->title }}</a>
                                    </h3>
                                    <p class="mt-2 line-clamp-3 text-sm text-inverse-on/80">{{ $article->excerpt }}</p>
                                    <p class="mt-4 text-xs text-inverse-on/60">{{ $article->author->name }} · {{ $article->readingMinutes() }} min baca</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 {{ in_array($category->slug, ['daerah', 'for-her'], true) ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }}">
                        @foreach ($category->homeArticles as $article)
                            <x-story-card :article="$article" />
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach

        @if (! $hero && $trending->isEmpty() && $blocks->isEmpty())
            <p class="py-20 text-center text-outline">Belum ada berita yang terbit.</p>
        @endif
    </div>
@endsection
