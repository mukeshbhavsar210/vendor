<?php

namespace App\Filament\Resources\CategoryImages\Pages;

use App\Filament\Resources\CategoryImages\CategoryImageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryImage extends CreateRecord
{
    protected static string $resource = CategoryImageResource::class;
}
