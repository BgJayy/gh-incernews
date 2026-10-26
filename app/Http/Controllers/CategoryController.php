<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $category = Category::query()->where('slug', $slug)->firstOrFail();

        $articles = $category->articles()
            ->published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(9);

        return view('categories.show', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }
}
