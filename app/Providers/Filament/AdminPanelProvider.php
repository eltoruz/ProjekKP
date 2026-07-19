<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandLogo('https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png')
            ->brandLogoHeight('2.5rem')
            ->favicon('https://pelayanan.data.kemendikdasmen.go.id/assets/imge/tutwuri.png')
            ->colors([
                'primary' => Color::hex('#1998ff'),
                'success' => Color::Green,
                'danger' => Color::Red,
                'warning' => Color::Amber,
            ])
            ->brandName('Pusdatin Admin')
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(false)
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Data Kerja Sama')
                    ->icon('heroicon-o-document-text'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\AutoLoginAdmin::class,
            ])
            ->authGuard('web')
            ->databaseNotifications(false);
    }
}
