<?php

namespace App\Filament\Resources\CategoryImages\Pages;

use App\Filament\Resources\CategoryImages\CategoryImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoryImages extends ListRecords
{
    protected static string $resource = CategoryImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
