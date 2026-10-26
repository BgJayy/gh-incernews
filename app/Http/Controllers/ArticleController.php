<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show(string $slug): View
    {
        $article = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->where('category_id', $article->category_id)
            ->whereKeyNot($article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('articles.show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
