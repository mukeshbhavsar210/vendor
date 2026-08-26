<?php

namespace App\Filament\Resources\CategoryImages;

use App\Filament\Resources\CategoryImages\Pages\CreateCategoryImage;
use App\Filament\Resources\CategoryImages\Pages\EditCategoryImage;
use App\Filament\Resources\CategoryImages\Pages\ListCategoryImages;
use App\Filament\Resources\CategoryImages\Schemas\CategoryImageForm;
use App\Filament\Resources\CategoryImages\Tables\CategoryImagesTable;
use App\Models\CategoryImage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CategoryImageResource extends Resource {
    protected static ?string $model = CategoryImage::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Repeater::make('images')
                    ->relationship('images')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('services/gallery')
                            ->required(),

                        TextInput::make('alt_text')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->maxLength(255),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_primary')
                            ->label('Primary Image'),
                    ])
                    ->reorderable('sort_order')
                    ->collapsible()
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return CategoryImagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoryImages::route('/'),
            'create' => CreateCategoryImage::route('/create'),
            'edit' => EditCategoryImage::route('/{record}/edit'),
        ];
    }
}
