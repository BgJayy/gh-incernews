<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = $this->publishedWithImage()
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(5)
            ->get();

        if ($featured->count() < 5) {
            $featured = $featured->concat(
                $this->publishedWithImage()
                    ->when($featured->isNotEmpty(), fn (Builder $query) => $query->whereNotIn('id', $featured->modelKeys()))
                    ->latest('published_at')
                    ->limit(5 - $featured->count())
                    ->get(),
            )->values();
        }

        $trending = $this->publishedWithRelations()
            ->where('is_trending', true)
            ->when($featured->isNotEmpty(), fn (Builder $query) => $query->whereNotIn('id', $featured->modelKeys()))
            ->latest('published_at')
            ->limit(4)
            ->get();

        if ($trending->count() < 4) {
            $exclude = collect($featured->modelKeys())->merge($trending->modelKeys());

            $trending = $trending->concat(
                $this->publishedWithRelations()
                    ->when($exclude->isNotEmpty(), fn (Builder $query) => $query->whereNotIn('id', $exclude->all()))
                    ->latest('published_at')
                    ->limit(4 - $trending->count())
                    ->get(),
            )->values();
        }

        $used = collect($featured->modelKeys())->merge($trending->modelKeys());

        $categories = Category::query()->orderBy('sort_order')->get();

        $articlesByCategory = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->whereIn('category_id', $categories->modelKeys())
            ->latest('published_at')
            ->get()
            ->groupBy('category_id');

        $blocks = $categories
            ->map(function (Category $category) use ($used, $articlesByCategory) {
                /** @var Collection<int, Article> $articles */
                $articles = $articlesByCategory->get($category->id, new Collection);

                $limit = match ($category->slug) {
                    'daerah', 'for-her' => 4,
                    'liputan-khusus' => 2,
                    default => 3,
                };

                $fresh = $articles->reject(fn (Article $article) => $used->contains($article->id))->take($limit)->values();
                $category->setRelation('homeArticles', $fresh->isNotEmpty() ? $fresh : $articles->take($limit)->values());

                return $category;
            })
            ->filter(fn (Category $category) => $category->homeArticles->isNotEmpty())
            ->values();

        return view('home', [
            'hero' => $featured->first(),
            'featured' => $featured,
            'trending' => $trending,
            'blocks' => $blocks,
        ]);
    }

    /**
     * @return Builder<Article>
     */
    private function publishedWithRelations(): Builder
    {
        return Article::query()
            ->published()
            ->with(['category', 'author']);
    }

    /**
     * @return Builder<Article>
     */
    private function publishedWithImage(): Builder
    {
        return $this->publishedWithRelations()
            ->whereNotNull('featured_image')
            ->where('featured_image', '!=', '');
    }
}
