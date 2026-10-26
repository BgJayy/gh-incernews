<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::deleting(function (Category $category): bool {
            return $category->articles()->doesntExist();
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function scopeWithoutPublishedArticles(Builder $query): Builder
    {
        return $query->whereDoesntHave('articles', function (Builder $articles): void {
            $articles->published();
        });
    }

    public function badgeClass(): string
    {
        return match ($this->slug) {
            'daerah' => 'bg-secondary text-white',
            'liputan-khusus' => 'bg-inverse text-white',
            'for-her' => 'bg-tertiary text-white',
            default => 'bg-primary text-white',
        };
    }

    public function accentClass(): string
    {
        return match ($this->slug) {
            'daerah' => 'bg-secondary',
            'liputan-khusus', 'for-her' => 'bg-tertiary',
            default => 'bg-primary',
        };
    }

    public function linkClass(): string
    {
        return match ($this->slug) {
            'daerah' => 'text-secondary',
            'for-her' => 'text-tertiary',
            default => 'text-primary',
        };
    }
}
