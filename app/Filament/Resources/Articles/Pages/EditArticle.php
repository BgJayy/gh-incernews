<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open')
                ->label('Lihat di situs')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (Article $record): string => route('articles.show', $record->slug))
                ->openUrlInNewTab()
                ->visible(fn (Article $record): bool => $record->isLive()),
            DeleteAction::make(),
        ];
    }
}
