<?php

namespace App\Filament\Resources\ServiceImages\Pages;

use App\Filament\Resources\ServiceImages\ServiceImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceImages extends ListRecords
{
    protected static string $resource = ServiceImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
