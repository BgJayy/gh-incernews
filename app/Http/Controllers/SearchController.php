<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $articles = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('body', 'like', "%{$query}%");
                });
            })
            ->when($query === '', fn ($builder) => $builder->whereRaw('1 = 0'))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('search', [
            'query' => $query,
            'articles' => $articles,
        ]);
    }
}
