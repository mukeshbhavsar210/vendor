<?php

namespace App\Filament\Resources\Vendors;

use App\Filament\Resources\Vendors\Pages\CreateVendor;
use App\Filament\Resources\Vendors\Pages\EditVendor;
use App\Filament\Resources\Vendors\Pages\ListVendors;
use App\Filament\Resources\Vendors\Schemas\VendorForm;
use App\Filament\Resources\Vendors\Tables\VendorsTable;
use App\Models\Vendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Str;
use Filament\Tables\Columns\ViewColumn;

class VendorResource extends Resource {
    protected static ?string $model = Vendor::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static function form(Schema $schema): Schema {
        return $schema
            ->components([                                                                          
                Section::make('Vendor Account')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Select::make('user_id')->label('User')->relationship('user', 'name')->searchable()->preload()->required(),
                                Select::make('status')
                                    ->options([
                                        'pending'  => 'Pending',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'blocked'  => 'Blocked',
                                    ])
                                    ->default('pending')->required(),

                                    Select::make('categories')->label('Categories')->relationship('categories', 'name')->multiple()
                                        ->searchable()->preload(),
                                    Toggle::make('is_verified')->label('Verified Vendor')->default(false),
                                    Textarea::make('admin_note')->label('Admin Note')->rows(3)->columnSpan(4),
                        ])
                    ])->columns(1)->columnSpanFull()->collapsible(),                        
                // Business
                Section::make('Business Information')
                    ->schema([
                         Grid::make(2)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        FileUpload::make('logo')->label('Logo')->image()->disk('public')->directory('vendors/logos')->imageEditor()->columnSpan(2)
                                            ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                                                $businessName = Str::slug($get('business_name') ?? 'vendor');
                                                $userId = auth()->id();
                                                return $businessName . '_' . $userId . '.' . $file->getClientOriginalExtension();
                                            }),
                                        TextInput::make('business_name')->label('Business Name')->required()->maxLength(255)->columnSpan(2),
                                        Textarea::make('description')->rows(3)->columnSpan(2),
                                ]),
                                Grid::make(2)
                                    ->schema([
                                        FileUpload::make('cover_image')->label('Cover Image')->image()->disk('public')->directory('vendors/covers')->imageEditor()->columnSpan(2)
                                            ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                                                $businessName = Str::slug($get('business_name') ?? 'vendor');
                                                $userId = auth()->id();

                                                return $businessName . '_cover_' . $userId . '.' . $file->getClientOriginalExtension();
                                            }),
                                        TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->columnSpan(2),
                                        TextInput::make('phone')->tel()->maxLength(20),
                                        TextInput::make('email')->email()->maxLength(255),
                                    ])
                            ])
                        ])->columnSpanFull()->collapsible(),               
                // Address
                Section::make('Business Address')
                    ->schema([
                        Grid::make(5)
                            ->schema([
                                Textarea::make('address')->rows(3)->columnSpan(5),
                                TextInput::make('city')->maxLength(100)->columnSpan(1),
                                TextInput::make('state')->maxLength(100)->columnSpan(1),
                                TextInput::make('pincode')->label('Pincode')->maxLength(10)->columnSpan(1),
                                TextInput::make('latitude')->numeric()->step('any')->columnSpan(1),
                                TextInput::make('longitude')->numeric()->step('any')->columnSpan(1),
                            ]),
                    ])->columnSpanFull()->collapsible(),                
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                ImageColumn::make('logo')->disk('public')->size(60)->circular(),
                // ViewColumn::make('vendor')->label('Vendor')
                //     ->view('filament.vendor_details', [
                //         'vendor' => fn ($record) => $record,
                //     ]), 

                TextColumn::make('business_name')->label('Vendor')
                    ->description(function ($record) {
                        return collect([
                            $record->user?->name ?? 'No Owner',
                            $record->city ?? 'No Owner',                            
                        ])->implode(' • ');
                    })->searchable()->sortable(),

                TextColumn::make('categories.name')->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'approved' => 'success',
                        'pending'  => 'warning',
                        'rejected' => 'danger',
                        'blocked'  => 'danger',
                        default    => 'gray',
                    }),
                IconColumn::make('is_verified')->boolean(),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'blocked'  => 'Blocked',
                    ]),

                TernaryFilter::make('is_verified')->label('Verified'),
            ])
            ->actions([
                EditAction::make()->icon('heroicon-o-pencil')->label(''),
                DeleteAction::make()->icon('heroicon-o-trash')->label('')->requiresConfirmation(),
            ]);
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
            'index' => ListVendors::route('/'),
            'create' => CreateVendor::route('/create'),
            'edit' => EditVendor::route('/{record}/edit'),
        ];
    }
}
