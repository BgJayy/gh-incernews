@props(['article', 'variant' => 'grid'])

@php
    $href = route('articles.show', $article->slug);
    $alt = $article->imageAlt();
@endphp

@if ($variant === 'row')
    <article class="group flex gap-4 rounded-lg bg-surface-lowest p-3 shadow-sm">
        <a href="{{ $href }}" class="relative h-20 w-20 shrink-0 overflow-hidden rounded bg-surface-low">
            @if ($article->image_url)
                <img src="{{ $article->image_url }}" alt="{{ $alt }}" width="160" height="160" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
            @endif
        </a>
        <div class="min-w-0 flex-1">
            <div class="mb-1 flex items-center gap-2">
                <span class="rounded px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider {{ $article->category->badgeClass() }}">{{ $article->category->name }}</span>
                <span class="text-xs text-outline">{{ $article->published_at?->diffForHumans() }}</span>
            </div>
            <h3 class="text-base font-bold leading-snug group-hover:text-primary">
                <a href="{{ $href }}">{{ $article->title }}</a>
            </h3>
        </div>
    </article>
@elseif ($variant === 'horizontal')
    <article class="group flex flex-col gap-4 rounded-xl bg-surface-lowest p-4 shadow-sm md:flex-row">
        <a href="{{ $href }}" class="relative aspect-[16/10] overflow-hidden rounded-lg bg-surface-low md:aspect-auto md:h-40 md:w-56">
            @if ($article->image_url)
                <img src="{{ $article->image_url }}" alt="{{ $alt }}" width="480" height="300" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
            @endif
            <span class="absolute top-2 left-2 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider {{ $article->category->badgeClass() }}">{{ $article->category->name }}</span>
        </a>
        <div class="flex min-w-0 flex-1 flex-col justify-between">
            <div>
                <p class="text-xs text-outline">{{ $article->publishedLabel() }} · {{ $article->readingMinutes() }} min baca</p>
                <h2 class="mt-1 text-lg font-bold leading-snug group-hover:text-primary">
                    <a href="{{ $href }}">{{ $article->title }}</a>
                </h2>
                <p class="mt-2 line-clamp-2 text-sm text-on-surface-variant">{{ $article->excerpt }}</p>
            </div>
            <p class="mt-3 text-sm font-semibold">{{ $article->author->name }}</p>
        </div>
    </article>
@else
    <article class="group flex flex-col overflow-hidden rounded-lg bg-surface-lowest shadow-sm">
        <a href="{{ $href }}" class="relative aspect-[16/10] overflow-hidden bg-surface-low">
            @if ($article->image_url)
                <img src="{{ $article->image_url }}" alt="{{ $alt }}" width="640" height="400" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
            @endif
            <span class="absolute top-2.5 left-2.5 rounded-full px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider {{ $article->category->badgeClass() }}">{{ $article->category->name }}</span>
        </a>
        <div class="flex flex-1 flex-col justify-between p-4">
            <div>
                <p class="text-xs text-outline">{{ $article->publishedLabel() }} · {{ $article->readingMinutes() }} min baca</p>
                <h3 class="mt-1 text-lg font-bold leading-snug group-hover:text-primary">
                    <a href="{{ $href }}">{{ $article->title }}</a>
                </h3>
                <p class="mt-2 line-clamp-2 text-sm text-on-surface-variant">{{ $article->excerpt }}</p>
            </div>
            <p class="mt-4 text-xs text-on-surface-variant">{{ $article->author->name }}</p>
        </div>
    </article>
@endif
