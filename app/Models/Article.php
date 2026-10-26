<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Article extends Model implements HasRichContent
{
    use InteractsWithRichContent;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'featured_image_alt',
        'featured_image_caption',
        'seo_title',
        'seo_description',
        'status',
        'published_at',
        'is_featured',
        'is_trending',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            if (blank($article->slug)) {
                $article->slug = Str::slug($article->title);
            }

            if (in_array($article->slug, self::reservedSlugs(), true)) {
                throw ValidationException::withMessages([
                    'slug' => 'Slug ini dipakai halaman situs dan tidak bisa dipakai artikel.',
                ]);
            }

            if ($article->status === 'published' && $article->published_at === null) {
                $article->published_at = now();
            }
        });
    }

    /**
     * @return list<string>
     */
    public static function reservedSlugs(): array
    {
        return [
            'admin',
            'build',
            'cari',
            'kategori',
            'livewire',
            'robots.txt',
            'sitemap.xml',
            'storage',
            'up',
        ];
    }

    public function isLive(): bool
    {
        return $this->status === 'published'
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }

    public function setUpRichContent(): void
    {
        $this->registerRichContent('body')
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsVisibility('public');
    }

    public function sanitizedBody(): string
    {
        return $this->renderRichContent('body');
    }

    public function schemaJson(): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $this->title,
            'description' => $this->seo_description ?: $this->excerpt,
            'image' => array_values(array_filter([$this->image_url])),
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->author?->name,
            ],
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name' => 'Incer News',
                'url' => route('home'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo-incernews.png'),
                ],
            ],
            'mainEntityOfPage' => route('articles.show', $this->slug),
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeIncompleteMedia(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query
                ->whereNull('featured_image')
                ->orWhere('featured_image', '')
                ->orWhereNull('featured_image_alt')
                ->orWhere('featured_image_alt', '');
        });
    }

    public function isMediaIncomplete(): bool
    {
        return blank($this->featured_image) || blank($this->featured_image_alt);
    }

    public function headline(): string
    {
        return $this->seo_title ?: $this->title;
    }

    public function readingMinutes(): int
    {
        $words = preg_match_all('/\p{L}+/u', strip_tags((string) $this->body)) ?: 0;

        return max(1, (int) ceil($words / 200));
    }

    public function publishedLabel(): string
    {
        return $this->published_at?->translatedFormat('d M Y') ?? '';
    }

    public function publishedFullLabel(): string
    {
        if ($this->published_at === null) {
            return '';
        }

        return $this->published_at->translatedFormat('d F Y, H:i').' WIB';
    }

    public function imageAlt(): string
    {
        return filled($this->featured_image_alt) ? $this->featured_image_alt : $this->title;
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (blank($this->featured_image)) {
                return null;
            }

            if (Str::startsWith($this->featured_image, ['http://', 'https://'])) {
                return $this->featured_image;
            }

            return Storage::disk('public')->url($this->featured_image);
        });
    }
}
