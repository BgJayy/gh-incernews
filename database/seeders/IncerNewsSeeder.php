<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IncerNewsSeeder extends Seeder
{
    public function run(): void
    {
        $payload = json_decode(File::get(database_path('data/incer-posts.json')), true);

        $categories = collect([
            ['slug' => 'daerah', 'name' => 'Daerah', 'description' => 'Kabar dari Kalimantan Barat dan daerah sekitarnya.', 'sort_order' => 1, 'wp_id' => 22],
            ['slug' => 'liputan-khusus', 'name' => 'Liputan Khusus', 'description' => 'Liputan mendalam seputar kegiatan sosial dan keagamaan.', 'sort_order' => 2, 'wp_id' => 23],
            ['slug' => 'for-her', 'name' => 'For Her', 'description' => 'Pemberdayaan perempuan dan kemandirian.', 'sort_order' => 3, 'wp_id' => 24],
            ['slug' => 'nasional', 'name' => 'Nasional', 'description' => 'Perkembangan yang berskala nasional.', 'sort_order' => 4, 'wp_id' => 21],
            ['slug' => 'opini', 'name' => 'Opini', 'description' => 'Pandangan dan catatan redaksi.', 'sort_order' => 5, 'wp_id' => 20],
        ])->mapWithKeys(function (array $item) {
            $category = Category::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'sort_order' => $item['sort_order'],
                ],
            );

            return [$item['wp_id'] => $category];
        });

        $desk = Author::query()->updateOrCreate(
            ['slug' => 'redaksi-incer-news'],
            ['name' => 'Redaksi Incer News', 'role' => 'Redaksi'],
        );

        $authors = [
            'redaksi' => $desk,
        ];

        $posts = collect($payload['posts'])
            ->reject(fn (array $post) => $post['slug'] === 'hello-world')
            ->sortByDesc('date')
            ->values();

        $featured = $posts->take(4)->pluck('slug');
        $trending = $posts->slice(4, 6)->pluck('slug');

        foreach ($posts as $post) {
            $authorName = trim((string) $post['author']);
            if ($authorName === '' || str_contains($authorName, '@')) {
                $author = $desk;
            } else {
                $author = $authors[$authorName] ??= Author::query()->updateOrCreate(
                    ['slug' => Str::slug($authorName)],
                    ['name' => $authorName, 'role' => 'Kontributor'],
                );
            }

            $categoryId = collect($post['categories'])->first(fn ($id) => $categories->has($id));
            $category = $categories->get($categoryId) ?? $categories->get(22);

            Article::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'category_id' => $category->id,
                    'author_id' => $author->id,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'] ?: Str::limit(trim(strip_tags($post['content'])), 220),
                    'body' => $this->localizeBody($post['content']),
                    'featured_image' => $this->storeImage($post['image'] ?? null, $post['slug']),
                    'featured_image_alt' => $post['title'],
                    'seo_title' => $post['title'],
                    'seo_description' => Str::limit($post['excerpt'] ?: '', 160, ''),
                    'status' => 'published',
                    'published_at' => $post['date'],
                    'is_featured' => $featured->contains($post['slug']),
                    'is_trending' => $trending->contains($post['slug']),
                ],
            );
        }
    }

    private function cleanBody(string $html): string
    {
        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? $html;

        return trim($html);
    }

    private function localizeBody(string $html): string
    {
        $html = $this->cleanBody($html);

        $html = preg_replace_callback(
            '#(?:https?:)?//incernews\.com(/wp-content/uploads/[^"\'\s>]+)#i',
            function (array $match): string {
                $stored = $this->cacheRemoteImage('https://incernews.com'.$match[1], ltrim($match[1], '/'));

                return $stored ? '/storage/'.$stored : $match[0];
            },
            $html,
        ) ?? $html;

        $html = preg_replace(
            '#<a\b[^>]*href=["\']https?://incernews\.com/[^"\']+["\'][^>]*>\s*(<img\b[^>]*>)\s*</a>#i',
            '$1',
            $html,
        ) ?? $html;

        return trim($html);
    }

    private function cacheRemoteImage(string $url, string $relative): ?string
    {
        $dest = storage_path('app/public/'.$relative);

        if (File::exists($dest) && File::size($dest) > 0) {
            return $relative;
        }

        try {
            $response = Http::timeout(45)->retry(2, 500)->get($url);
            if (! $response->successful()) {
                return null;
            }

            File::ensureDirectoryExists(dirname($dest));
            File::put($dest, $response->body());

            return $relative;
        } catch (\Throwable) {
            return null;
        }
    }

    private function storeImage(?string $url, string $slug): ?string
    {
        if (blank($url)) {
            return null;
        }

        $local = $this->localPathForUrl($url);
        if ($local && File::exists($local) && File::size($local) > 0) {
            return $this->resize($local, $slug);
        }

        try {
            $response = Http::timeout(45)->retry(2, 500)->get($url);
            if (! $response->successful()) {
                return $local && File::exists($local) ? $this->resize($local, $slug) : null;
            }

            $temporary = tempnam(sys_get_temp_dir(), 'incer');
            File::put($temporary, $response->body());
            $stored = $this->resize($temporary, $slug);
            File::delete($temporary);

            return $stored;
        } catch (\Throwable) {
            return $local && File::exists($local) ? $this->resize($local, $slug) : null;
        }
    }

    private function localPathForUrl(string $url): ?string
    {
        if (! preg_match('#/wp-content/uploads/(.+)$#i', $url, $match)) {
            return null;
        }

        return storage_path('app/public/wp-content/uploads/'.$match[1]);
    }

    private function resize(string $source, string $slug): ?string
    {
        $info = @getimagesize($source);
        if ($info === false) {
            return null;
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($source),
            default => false,
        };

        if ($image === false) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $max = 1200;

        if ($width > $max) {
            $targetWidth = $max;
            $targetHeight = (int) round($height * ($max / $width));
        } else {
            $targetWidth = $width;
            $targetHeight = $height;
        }

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
        imagedestroy($image);

        File::ensureDirectoryExists(storage_path('app/public/articles'));

        if (function_exists('imagewebp')) {
            $relative = "articles/{$slug}.webp";
            imagewebp($canvas, storage_path('app/public/'.$relative), 78);
        } else {
            $relative = "articles/{$slug}.jpg";
            imagejpeg($canvas, storage_path('app/public/'.$relative), 80);
        }

        imagedestroy($canvas);

        return $relative;
    }
}
