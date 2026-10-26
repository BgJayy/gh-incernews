<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Author extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'role',
    ];

    protected static function booted(): void
    {
        static::saving(function (Author $author): void {
            if (blank($author->slug)) {
                $author->slug = Str::slug($author->name);
            }
        });

        static::deleting(function (Author $author): bool {
            return $author->articles()->doesntExist();
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function initials(): string
    {
        $skip = ['prof', 'dr', 'kh', 'ir', 'hj', 'h', 's.ag', 'm.a', 'm.ag'];

        $letters = collect(preg_split('/\s+/', trim($this->name)) ?: [])
            ->filter()
            ->reject(function (string $part) use ($skip): bool {
                $normalized = mb_strtolower(trim($part, '.,'));

                return in_array($normalized, $skip, true);
            })
            ->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)));

        return $letters->isNotEmpty()
            ? $letters->implode('')
            : mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
