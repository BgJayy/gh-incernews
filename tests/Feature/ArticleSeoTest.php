<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_alt_falls_back_to_title(): void
    {
        $article = $this->makeArticle([
            'title' => 'Judul cadangan alt',
            'featured_image_alt' => null,
        ]);

        $this->assertSame('Judul cadangan alt', $article->imageAlt());
    }

    public function test_article_page_uses_alt_and_caption(): void
    {
        $article = $this->makeArticle([
            'title' => 'Judul halaman',
            'featured_image' => 'articles/contoh.webp',
            'featured_image_alt' => 'PLTS terapung Cirata saat matahari terbit',
            'featured_image_caption' => 'Dok. Incer News',
        ]);

        $this->get('/'.$article->slug)
            ->assertOk()
            ->assertSee('alt="PLTS terapung Cirata saat matahari terbit"', false)
            ->assertSee('Dok. Incer News')
            ->assertSee('og:image:alt', false)
            ->assertSee('PLTS terapung Cirata saat matahari terbit')
            ->assertSee('"logo":{"@type":"ImageObject"', false)
            ->assertSee('images/logo-incernews.png', false);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeArticle(array $overrides = []): Article
    {
        $category = Category::query()->create([
            'name' => 'Nasional',
            'slug' => 'nasional',
            'description' => 'Kanal uji',
            'sort_order' => 1,
        ]);

        $author = Author::query()->create([
            'name' => 'Redaksi Uji',
            'slug' => 'redaksi-uji',
            'role' => 'Redaksi',
        ]);

        return Article::query()->create(array_merge([
            'category_id' => $category->id,
            'author_id' => $author->id,
            'title' => 'Artikel uji',
            'slug' => 'artikel-uji',
            'excerpt' => 'Ringkasan artikel uji untuk kartu dan SEO.',
            'body' => '<p>Isi artikel uji.</p>',
            'featured_image' => 'articles/uji.webp',
            'featured_image_alt' => 'Alt uji',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ], $overrides));
    }
}
