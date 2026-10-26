@extends('layouts.site')

@section('title', $article->headline().' — Incer News')
@section('description', $article->seo_description ?: $article->excerpt)
@section('canonical', route('articles.show', $article->slug))
@section('og_type', 'article')
@section('progress', true)
@section('og_image', $article->image_url ?: '')
@section('og_image_alt', $article->image_url ? $article->imageAlt() : '')

@php
    $shareUrl = urlencode(route('articles.show', $article->slug));
    $shareText = urlencode($article->title);
@endphp

@push('head')
    <script type="application/ld+json">
        {!! $article->schemaJson() !!}
    </script>
@endpush

@section('content')
    <div class="mx-auto max-w-[1400px] px-4 py-6 md:px-6 lg:px-8">
        <div class="mx-auto max-w-[1040px]">
            <nav aria-label="Breadcrumb" class="mb-4 flex items-center gap-2 text-sm text-outline">
                <a href="{{ route('home') }}" class="hover:text-secondary">Beranda</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('categories.show', $article->category->slug) }}" class="text-secondary hover:underline">{{ $article->category->name }}</a>
                <span aria-hidden="true">/</span>
                <span class="truncate font-semibold text-on-surface">{{ $article->title }}</span>
            </nav>

            <div class="mb-3">
                <a href="{{ route('categories.show', $article->category->slug) }}" class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $article->category->badgeClass() }}">{{ $article->category->name }}</a>
            </div>

            <h1 class="mb-4 text-3xl leading-tight font-extrabold tracking-tight md:text-4xl">{{ $article->title }}</h1>

            <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded bg-surface-low px-4 py-3">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-on-surface-variant">
                    <span class="font-semibold text-on-surface">{{ $article->author->name }}</span>
                    @if ($article->author->role)
                        <span>{{ $article->author->role }}</span>
                    @endif
                    <span>{{ $article->publishedFullLabel() }}</span>
                    <span class="font-semibold text-primary">{{ $article->readingMinutes() }} menit baca</span>
                </div>
            </div>

            <div class="mb-6 flex flex-wrap items-center gap-2 rounded bg-surface-lowest px-4 py-2 shadow-sm">
                <span class="text-[11px] font-bold tracking-wider text-outline uppercase">Bagikan</span>
                <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" rel="noopener noreferrer" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-surface-low text-[#25D366] hover:bg-[#25D366] hover:text-white" aria-label="Bagikan ke WhatsApp">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" rel="noopener noreferrer" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-surface-low text-on-surface hover:bg-black hover:text-white" aria-label="Bagikan ke X">
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" rel="noopener noreferrer" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-surface-low text-[#1877F2] hover:bg-[#1877F2] hover:text-white" aria-label="Bagikan ke Facebook">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <button type="button" data-copy-url class="flex h-8 w-8 items-center justify-center rounded-full bg-surface-low text-outline hover:bg-secondary hover:text-white" aria-label="Salin tautan" title="Salin tautan">
                    <svg viewBox="0 0 24 24" class="h-4 w-4 group-data-[copied]:hidden" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.07 0l2.83-2.83a5 5 0 0 0-7.07-7.07L11 4"/><path d="M14 11a5 5 0 0 0-7.07 0L4.1 13.83a5 5 0 0 0 7.07 7.07L13 20"/></svg>
                </button>
            </div>
        </div>

        @if ($article->image_url)
            <figure class="mx-auto mb-10 max-w-[1040px]">
                <div class="aspect-[16/9] overflow-hidden rounded bg-surface-low">
                    <img src="{{ $article->image_url }}" alt="{{ $article->imageAlt() }}" width="1200" height="675" class="h-full w-full object-cover">
                </div>
                @if (filled($article->featured_image_caption))
                    <figcaption class="mt-2 text-sm text-outline">{{ $article->featured_image_caption }}</figcaption>
                @endif
            </figure>
        @endif

        <article class="mx-auto mb-12 max-w-[760px] rounded bg-surface-lowest p-4 shadow-sm sm:p-10">
            @if ($article->excerpt)
                <p class="mb-6 text-xl leading-8 font-medium">{{ $article->excerpt }}</p>
            @endif
            <div class="article-body">
                {!! $article->sanitizedBody() !!}
            </div>
        </article>

        <section class="mx-auto mb-8 max-w-[760px] rounded bg-surface-low p-6">
            <div class="flex items-center gap-4">
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-primary text-lg font-bold text-white">{{ $article->author->initials() }}</span>
                <div>
                    <h2 class="text-lg font-bold">{{ $article->author->name }}</h2>
                    @if ($article->author->role)
                        <p class="text-sm font-semibold text-secondary">{{ $article->author->role }}</p>
                    @endif
                </div>
            </div>
        </section>

        @if ($related->isNotEmpty())
            <section class="mx-auto mt-8 max-w-[1040px]">
                <div class="mb-6 flex items-center gap-2">
                    <span class="h-6 w-1.5 rounded-full bg-primary"></span>
                    <h2 class="text-2xl font-bold tracking-tight">Berita terkait</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($related as $item)
                        <x-story-card :article="$item" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
