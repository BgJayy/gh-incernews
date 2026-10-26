<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

        View::composer('layouts.site', function ($view): void {
            $preferred = ['opini', 'nasional', 'daerah', 'liputan-khusus', 'for-her'];

            $categories = Category::query()
                ->orderBy('sort_order')
                ->get()
                ->sortBy(function (Category $category) use ($preferred): int {
                    $index = array_search($category->slug, $preferred, true);

                    return $index === false ? 100 + $category->sort_order : $index;
                })
                ->values();

            $view->with([
                'navCategories' => $categories,
                'breakingArticle' => Article::query()
                    ->published()
                    ->latest('published_at')
                    ->first(),
            ]);
        });
    }
}
