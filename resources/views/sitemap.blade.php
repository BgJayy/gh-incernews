<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if (config('app.indexing'))
    <url>
        <loc>{{ route('home') }}</loc>
    </url>
    @endif
    @foreach ($categories as $category)
        <url>
            <loc>{{ route('categories.show', $category->slug) }}</loc>
        </url>
    @endforeach
    @foreach ($articles as $article)
        <url>
            <loc>{{ route('articles.show', $article->slug) }}</loc>
            @if ($article->updated_at)
                <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
            @endif
        </url>
    @endforeach
</urlset>
