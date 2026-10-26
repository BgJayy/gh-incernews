<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class NeedsAttentionWidget extends TableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Perlu perhatian')
            ->description('Draf dan naskah terbit yang belum punya gambar atau teks alternatif.')
            ->query(
                Article::query()
                    ->with(['category', 'author'])
                    ->where(function (Builder $query): void {
                        $query->draft()
                            ->orWhere(function (Builder $query): void {
                                $query->published()->incompleteMedia();
                            });
                    })
                    ->orderByRaw("CASE WHEN status = 'published' THEN 0 ELSE 1 END")
                    ->orderByDesc('updated_at'),
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(70)
                    ->wrap(),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'published' ? 'Terbit' : 'Draf')
                    ->color(fn (string $state): string => $state === 'published' ? 'danger' : 'warning'),
                TextColumn::make('gap')
                    ->label('Kekurangan')
                    ->state(function (Article $record): string {
                        if ($record->status === 'draft') {
                            return $record->isMediaIncomplete()
                                ? 'Draf · gambar/alt'
                                : 'Draf';
                        }

                        $missing = [];

                        if (blank($record->featured_image)) {
                            $missing[] = 'Gambar';
                        }

                        if (blank($record->featured_image_alt)) {
                            $missing[] = 'Alt';
                        }

                        return implode(' · ', $missing) ?: '—';
                    }),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->since(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Ubah')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->url(fn (Article $record): string => ArticleResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Tidak ada yang menunggu')
            ->emptyStateDescription('Semua naskah terbit sudah punya gambar dan teks alternatif. Tidak ada draf.')
            ->emptyStateIcon(Heroicon::OutlinedCheckCircle);
    }
}
