<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Article;
use App\Models\Category;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EditorialStatsWidget extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan redaksi';

    protected ?string $description = 'Angka dari basis data, bukan estimasi kunjungan atau skor mesin pencari.';

    protected function getStats(): array
    {
        $published = Article::query()->published()->count();
        $drafts = Article::query()->draft()->count();
        $incomplete = Article::query()->published()->incompleteMedia()->count();
        $emptyCategories = Category::query()->withoutPublishedArticles()->orderBy('sort_order')->get();

        return [
            Stat::make('Terbit', $published)
                ->description('Tampil di situs sekarang')
                ->icon(Heroicon::OutlinedNewspaper)
                ->color('success')
                ->url(ArticleResource::getUrl('index', [
                    'filters' => [
                        'status' => ['value' => 'published'],
                    ],
                ])),
            Stat::make('Draf', $drafts)
                ->description('Belum diterbitkan')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->color('warning')
                ->url(ArticleResource::getUrl('index', [
                    'filters' => [
                        'status' => ['value' => 'draft'],
                    ],
                ])),
            Stat::make('Perlu dilengkapi', $incomplete)
                ->description('Terbit tanpa gambar atau teks alternatif')
                ->icon(Heroicon::OutlinedPhoto)
                ->color($incomplete > 0 ? 'danger' : 'success')
                ->url(ArticleResource::getUrl('index', [
                    'filters' => [
                        'status' => ['value' => 'published'],
                        'incomplete_media' => ['isActive' => true],
                    ],
                ])),
            Stat::make('Kanal kosong', $emptyCategories->count())
                ->description($emptyCategories->isEmpty()
                    ? 'Semua kanal punya berita terbit'
                    : $emptyCategories->pluck('name')->implode(', '))
                ->icon(Heroicon::OutlinedTag)
                ->color($emptyCategories->isNotEmpty() ? 'gray' : 'success')
                ->url(CategoryResource::getUrl('index')),
        ];
    }
}
