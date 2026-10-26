<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->disk('public'),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'published' ? 'Terbit' : 'Draf')
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                ToggleColumn::make('is_featured')
                    ->label('Slider'),
                ToggleColumn::make('is_trending')
                    ->label('Trending'),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draf',
                        'published' => 'Terbit',
                    ]),
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Filter::make('incomplete_media')
                    ->label('Tanpa gambar atau alt')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->incompleteMedia()),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Lihat')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Article $record): string => route('articles.show', $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (Article $record): bool => $record->isLive()),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
