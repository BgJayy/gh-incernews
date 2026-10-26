<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label('Artikel'),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action): void {
                            $records = $action->getRecords();

                            if ($records instanceof Collection && $records->contains(fn (Category $record): bool => $record->articles()->exists())) {
                                Notification::make()
                                    ->danger()
                                    ->title('Tidak bisa dihapus')
                                    ->body('Ada kategori yang masih punya artikel.')
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
