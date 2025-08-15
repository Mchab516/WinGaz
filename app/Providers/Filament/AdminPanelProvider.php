<?php

namespace App\Providers\Filament;

use App\Filament\Resources\ChargementsVentesResource\Pages\ReportingMensuel;
use App\Filament\Resources\ClientResource;
use App\Filament\Resources\CentreEmplisseurResource;
use App\Filament\Resources\ChargementsVentesResource;
use App\Filament\Resources\ProfilResource;
use App\Filament\Resources\UtilisateurResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(fn() => view('components.logo'))
            ->renderHook(
                'head.start',
                fn() => '<link rel="icon" type="image/x-icon" href="' . asset('winxo-favicon.ico') . '?v=' . time() . '" />'
            )
            ->theme(asset('css/filament/admin/theme.css'))
            ->colors([
                'primary' => Color::hex('#0094C9'),
            ])
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->resources($this->getResources())
            ->pages([
                Dashboard::class,
                // Page personnalisée (si tu veux un accès direct en plus de la resource).
                ReportingMensuel::class,
            ])
            ->widgets([
                \App\Filament\Admin\Widgets\DashboardKpis::class,
                \App\Filament\Admin\Widgets\VentesMensuellesChart::class,
                \App\Filament\Admin\Widgets\NouveauxClientsChart::class,
            ])
            // ---------- MENU UTILISATEUR (avatar) ----------
            ->userMenuItems([
                MenuItem::make()
                    ->label('Utilisateurs')
                    ->icon('heroicon-o-user-group')
                    ->url(fn() => UtilisateurResource::getUrl())
                    ->visible(fn() => Auth::user()?->profil_id === 1),

                MenuItem::make()
                    ->label('Profils')
                    ->icon('heroicon-o-shield-check')
                    ->url(fn() => ProfilResource::getUrl())
                    ->visible(fn() => Auth::user()?->profil_id === 1),
            ])
            // ------------------------------------------------
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]);
    }

    /**
     * Enregistrer TOUTES les resources pour que les routes existent,
     * puis gérer la visibilité/les permissions dans chaque resource.
     */
    private function getResources(): array
    {
        return [
            ClientResource::class,
            CentreEmplisseurResource::class,
            ChargementsVentesResource::class,
            UtilisateurResource::class, // routes toujours créées
            ProfilResource::class,      // routes toujours créées
        ];
    }
}
