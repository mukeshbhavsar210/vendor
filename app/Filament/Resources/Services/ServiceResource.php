<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;

class ServiceResource extends Resource {
    protected static ?string $model = Service::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([                        
                        Select::make('vendor_id')->relationship('vendor', 'business_name')->searchable()->preload()->required()->columnSpan(3),
                        TextInput::make('name')->required()->maxLength(255)->columnSpan(3),
                        Textarea::make('description')->columnSpan(3),
                    ]),                    
                    Grid::make(2)
                        ->schema([
                            Select::make('category_id')->relationship('category', 'name')->searchable()->preload()->required(),
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected',
                                ])
                    ->default('pending'),    
                            TextInput::make('price')->numeric(),
                            Select::make('price_type')
                                ->options([
                                    'fixed' => 'Fixed',
                                    'hourly' => 'Hourly',
                                    'starting_from' => 'Starting From',
                                    'quote' => 'Get Quote',
                            ]),
                            FileUpload::make('images')
                                ->multiple()->reorderable()->image()->disk('public')
                                ->directory('services')->columnSpan(2),

                                Toggle::make('is_featured'),
                        ]),

                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        FileUpload::make('image')->image()->disk('public')->directory('services/gallery')->required()->columnSpan(1),
                                    ]),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('alt_text')->maxLength(255),
                                            TextInput::make('title')->maxLength(255),
                                            TextInput::make('sort_order')->numeric()->default(0),
                                            Toggle::make('is_primary')->label('Primary Image'),
                                    ]),
                                ])      
                                ->reorderable('sort_order')
                                ->columnSpan(3)
            ]);
    }
    
    
    public static function table(Table $table): Table {
        return ServicesTable::configure($table);
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
