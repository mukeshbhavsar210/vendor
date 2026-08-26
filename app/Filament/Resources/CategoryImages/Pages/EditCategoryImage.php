<?php

namespace App\Filament\Resources\CategoryImages\Pages;

use App\Filament\Resources\CategoryImages\CategoryImageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryImage extends EditRecord
{
    protected static string $resource = CategoryImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
