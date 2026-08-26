<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                 Select::make('parent_id')->label('Parent Category')->relationship('parent', 'name')->searchable()
                    ->preload()->placeholder('Main Category'),
                TextInput::make('name')->required()->live(onBlur: true),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Textarea::make('description'),
                FileUpload::make('image')->image()->disk('public')->directory('categories'),
                TextInput::make('icon')->placeholder('heroicon-o-home'),
                TextInput::make('meta_title'),
                Textarea::make('meta_description'),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('status')->label('Active')->default(true),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                ImageColumn::make('image')->disk('public')->size(60)->circular(),
                //TextColumn::make('parent.name')->label('Parent Category')->searchable()->sortable(),
                TextColumn::make('name')->label('Name')->searchable()->sortable(),
                TextColumn::make('description')->label('Description')->searchable()->sortable(),                
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
