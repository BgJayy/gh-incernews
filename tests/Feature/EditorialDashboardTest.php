<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditorialDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_dashboard_shows_real_editorial_counts(): void
    {
        $this->seedDesk();

        $this->actingAs(User::factory()->create([
            'email' => 'redaksi@incernews.test',
        ]))
            ->get('/admin')
            ->assertOk()
            ->assertSee('Ringkasan redaksi')
            ->assertSee('Terbit')
            ->assertSee('Draf')
            ->assertSee('Perlu dilengkapi')
            ->assertSee('Kanal kosong')
            ->assertSee('Nasional')
            ->assertSee('Naskah draf uji')
            ->assertSee('Terbit tanpa alt')
            ->assertSee('Angka dari basis data')
            ->assertDontSee('Google Search Console');
    }

    public function test_user_outside_allowlist_cannot_open_admin(): void
    {
        $this->actingAs(User::factory()->create([
            'email' => 'bukan-redaksi@example.com',
        ]))
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_panel_requires_app_mfa_when_configured(): void
    {
        config(['app.mfa_required' => true]);

        $panel = \Filament\Facades\Filament::getPanel('admin');

        $this->assertTrue($panel->isMultiFactorAuthenticationRequired());
        $this->assertArrayHasKey('app', $panel->getMultiFactorAuthenticationProviders());
    }

    public function test_incomplete_media_scope_only_matches_missing_fields(): void
    {
        $this->seedDesk();

        $this->assertSame(2, Article::query()->published()->count());
        $this->assertSame(1, Article::query()->published()->incompleteMedia()->count());
        $this->assertSame(1, Article::query()->draft()->count());
        $this->assertSame(1, Category::query()->withoutPublishedArticles()->count());
    }

    private function seedDesk(): void
    {
        $nasional = Category::query()->create([
            'name' => 'Nasional',
            'slug' => 'nasional',
            'sort_order' => 1,
        ]);

        $daerah = Category::query()->create([
            'name' => 'Daerah',
            'slug' => 'daerah',
            'sort_order' => 2,
        ]);

        $author = Author::query()->create([
            'name' => 'Redaksi Uji',
            'slug' => 'redaksi-uji',
        ]);

        Article::query()->create([
            'category_id' => $daerah->id,
            'author_id' => $author->id,
            'title' => 'Berita lengkap',
            'slug' => 'berita-lengkap',
            'excerpt' => 'Ringkasan lengkap.',
            'body' => '<p>Isi.</p>',
            'featured_image' => 'articles/lengkap.webp',
            'featured_image_alt' => 'Foto lapangan',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        Article::query()->create([
            'category_id' => $daerah->id,
            'author_id' => $author->id,
            'title' => 'Terbit tanpa alt',
            'slug' => 'terbit-tanpa-alt',
            'excerpt' => 'Ringkasan tanpa alt.',
            'body' => '<p>Isi.</p>',
            'featured_image' => 'articles/tanpa-alt.webp',
            'featured_image_alt' => null,
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        Article::query()->create([
            'category_id' => $nasional->id,
            'author_id' => $author->id,
            'title' => 'Naskah draf uji',
            'slug' => 'naskah-draf-uji',
            'excerpt' => 'Masih draf.',
            'body' => '<p>Isi.</p>',
            'status' => 'draft',
        ]);
    }
}
