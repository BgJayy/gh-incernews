<?php

namespace Tests\Unit;

use Database\Seeders\IncerNewsSeeder;
use ReflectionMethod;
use Tests\TestCase;

class WordPressMediaLocalizerTest extends TestCase
{
    public function test_body_images_rewrite_to_local_storage(): void
    {
        $html = $this->localize(
            '<p><img src="https://incernews.com/wp-content/uploads/2025/04/cover.jpg" alt=""></p>',
        );

        $this->assertStringContainsString('/storage/wp-content/uploads/2025/04/cover.jpg', $html);
        $this->assertStringNotContainsString('https://incernews.com/wp-content/uploads', $html);
    }

    public function test_gallery_attachment_links_are_unwrapped(): void
    {
        $html = $this->localize(
            '<a href="https://incernews.com/artikel/cover-768x576/"><img src="https://incernews.com/wp-content/uploads/2025/04/cover.jpg" alt=""></a>',
        );

        $this->assertStringNotContainsString('<a href="https://incernews.com/artikel/cover-768x576/">', $html);
        $this->assertStringContainsString('<img src="/storage/wp-content/uploads/2025/04/cover.jpg"', $html);
    }

    private function localize(string $html): string
    {
        $method = new ReflectionMethod(IncerNewsSeeder::class, 'localizeBody');

        return $method->invoke(new IncerNewsSeeder, $html);
    }
}
