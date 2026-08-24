<?php

namespace App\Filament\Resources\ServiceImages;

use App\Filament\Resources\ServiceImages\Pages\CreateServiceImage;
use App\Filament\Resources\ServiceImages\Pages\EditServiceImage;
use App\Filament\Resources\ServiceImages\Pages\ListServiceImages;
use App\Filament\Resources\ServiceImages\Schemas\ServiceImageForm;
use App\Filament\Resources\ServiceImages\Tables\ServiceImagesTable;
use App\Models\ServiceImage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ServiceImageResource extends Resource {
    protected static ?string $model = ServiceImage::class;
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
        return ServiceImagesTable::configure($table);
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
            'index' => ListServiceImages::route('/'),
            'create' => CreateServiceImage::route('/create'),
            'edit' => EditServiceImage::route('/{record}/edit'),
        ];
    }
}
