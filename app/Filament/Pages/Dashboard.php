<?php

namespace App\Filament\Pages;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Dashboard extends Page
{
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected string $view = 'filament.pages.dashboard';    

    protected static ?string $slug = 'dashboard';

    protected static ?string $title = 'Dashboard';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}