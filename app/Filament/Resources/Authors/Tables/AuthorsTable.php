<?php

namespace App\Filament\Resources\Authors\Tables;

use App\Models\Author;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class AuthorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Peran'),
                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label('Artikel'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action): void {
                            $records = $action->getRecords();

                            if ($records instanceof Collection && $records->contains(fn (Author $record): bool => $record->articles()->exists())) {
                                Notification::make()
                                    ->danger()
                                    ->title('Tidak bisa dihapus')
                                    ->body('Ada penulis yang masih punya artikel.')
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
