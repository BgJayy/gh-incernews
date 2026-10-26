<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PublicIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_inline_body_image_renders_from_public_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('articles/body/inline.jpg', 'fake-image');

        $article = $this->makeArticle([
            'slug' => 'gambar-inline',
            'body' => '<p>Paragraf dengan foto.</p><img data-id="articles/body/inline.jpg" alt="Foto kegiatan">',
        ]);

        $this->get('/'.$article->slug)
            ->assertOk()
            ->assertSee('Paragraf dengan foto.')
            ->assertSee('alt="Foto kegiatan"', false)
            ->assertSee('/storage/articles/body/inline.jpg', false);
    }

    public function test_legacy_body_image_src_is_kept(): void
    {
        $article = $this->makeArticle([
            'slug' => 'gambar-warisan',
            'body' => '<p>Isi lama.</p><img src="/storage/wp-content/uploads/2025/04/cover.jpg" alt="Cover lama">',
        ]);

        $this->get('/'.$article->slug)
            ->assertOk()
            ->assertSee('src="/storage/wp-content/uploads/2025/04/cover.jpg"', false);
    }

    public function test_article_body_strips_scripts_and_event_handlers(): void
    {
        $article = $this->makeArticle([
            'body' => '<p>Teks aman</p><script>alert(1)</script><img src="x" onerror="alert(1)">',
        ]);

        $this->get('/'.$article->slug)
            ->assertOk()
            ->assertSee('Teks aman')
            ->assertDontSee('<script>', false)
            ->assertDontSee('onerror', false);
    }

    public function test_json_ld_escapes_script_breakout_in_title(): void
    {
        $article = $this->makeArticle([
            'title' => 'Pecah </script><script>alert(1)</script>',
            'slug' => 'pecah-judul',
        ]);

        $this->get('/'.$article->slug)
            ->assertOk()
            ->assertSee('"@context":"https://schema.org"', false)
            ->assertSee('"@type":"NewsArticle"', false)
            ->assertSee('\u003C/script\u003E', false)
            ->assertDontSee('</script><script>alert(1)</script>', false);
    }

    public function test_reserved_slug_is_rejected(): void
    {
        $this->expectException(ValidationException::class);

        $this->makeArticle(['slug' => 'cari']);
    }

    public function test_featured_article_stays_the_hero_when_fillers_exist(): void
    {
        $featured = $this->makeArticle([
            'title' => 'Naskah unggulan',
            'slug' => 'naskah-unggulan',
            'is_featured' => true,
            'published_at' => now()->subDays(10),
        ]);

        $this->makeArticle([
            'title' => 'Berita baru',
            'slug' => 'berita-baru',
            'is_featured' => false,
            'published_at' => now()->subHour(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Naskah unggulan')
            ->assertSee($featured->title);
    }

    public function test_trending_renders_when_there_is_no_hero_image(): void
    {
        $this->makeArticle([
            'title' => 'Tren tanpa gambar hero',
            'slug' => 'tren-tanpa-gambar',
            'featured_image' => null,
            'is_trending' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Tren & terpopuler')
            ->assertSee('Tren tanpa gambar hero');
    }

    public function test_site_logo_appears_in_header_and_favicon(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('images/logo-incernews.png', false)
            ->assertSee('rel="icon"', false)
            ->assertSee('rel="apple-touch-icon"', false)
            ->assertSee('alt="Incer News"', false);
    }

    public function test_robots_txt_uses_absolute_sitemap_url(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false);
    }

    public function test_staging_robots_disallows_indexing(): void
    {
        config(['app.indexing' => false]);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee("User-agent: *\nDisallow: /", false)
            ->assertDontSee('Sitemap:', false);
    }

    public function test_category_with_articles_cannot_be_deleted(): void
    {
        $article = $this->makeArticle();

        $this->assertFalse($article->category->delete());
        $this->assertDatabaseHas('categories', ['id' => $article->category_id]);
    }

    public function test_scheduled_article_is_not_live(): void
    {
        $article = $this->makeArticle([
            'slug' => 'belum-waktunya',
            'published_at' => now()->addDay(),
        ]);

        $this->assertFalse($article->isLive());
        $this->get('/belum-waktunya')->assertNotFound();
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeArticle(array $overrides = []): Article
    {
        $category = Category::query()->first() ?? Category::query()->create([
            'name' => 'Nasional',
            'slug' => 'nasional',
            'description' => 'Kanal uji',
            'sort_order' => 1,
        ]);

        $author = Author::query()->first() ?? Author::query()->create([
            'name' => 'Redaksi Uji',
            'slug' => 'redaksi-uji',
            'role' => 'Redaksi',
        ]);

        return Article::query()->create(array_merge([
            'category_id' => $category->id,
            'author_id' => $author->id,
            'title' => 'Artikel uji',
            'slug' => 'artikel-uji-'.uniqid(),
            'excerpt' => 'Ringkasan artikel uji untuk kartu dan SEO.',
            'body' => '<p>Isi artikel uji.</p>',
            'featured_image' => 'articles/uji.webp',
            'featured_image_alt' => 'Alt uji',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ], $overrides));
    }
}
