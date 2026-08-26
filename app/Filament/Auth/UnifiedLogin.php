<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;

class UnifiedLogin extends BaseLogin {

    protected function getRedirectUrl(): ?string
{
    $user = auth()->user();

    return match (true) {
        $user->hasRole('admin')  => url('/dashboard'),
        $user->hasRole('vendor') => Filament::getPanel('vendor')->getUrl(),
        default                  => Filament::getPanel('customer')->getUrl(),
    };
}

    // protected function getDefaultRedirectUrl(): string {
    //     $user = auth()->user();

    //     return match (true) {
    //         $user->hasRole('admin')    => Filament::getPanel('admin')->getUrl(),
    //         $user->hasRole('vendor')   => Filament::getPanel('vendor')->getUrl(),
    //         default => Filament::getPanel('customer')->getUrl(), // customer -> storefront
    //     };
    // }
}