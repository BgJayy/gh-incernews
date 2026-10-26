<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (): bool => $this->getRecord()->articles()->exists())
                ->tooltip(fn (): ?string => $this->getRecord()->articles()->exists()
                    ? 'Tidak bisa dihapus karena masih punya artikel.'
                    : null),
        ];
    }
}
