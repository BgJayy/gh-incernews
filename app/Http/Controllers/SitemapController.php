<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $articles = config('app.indexing')
            ? Article::query()->published()->latest('published_at')->get()
            : collect();
        $categories = config('app.indexing')
            ? Category::query()->orderBy('sort_order')->get()
            : collect();

        return response()
            ->view('sitemap', [
                'articles' => $articles,
                'categories' => $categories,
            ])
            ->header('Content-Type', 'application/xml');
    }
}
