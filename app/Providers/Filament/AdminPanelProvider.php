<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ChargementsVentesResource\Pages\ReportingMensuel;



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
                ReportingMensuel::class,
            ])

            ->widgets([
                \App\Filament\Admin\Widgets\CustomButtonsWidget::class,
            ])
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
     * Retourne les ressources visibles selon le profil connecté
     */
    private function getResources(): array
    {
        $resources = [
            \App\Filament\Resources\ClientResource::class,
            \App\Filament\Resources\CentreEmplisseurResource::class,
            \App\Filament\Resources\ChargementsVentesResource::class,
        ];

        if (Auth::check() && Auth::user()->profil_id === 1) {
            $resources[] = \App\Filament\Resources\UtilisateurResource::class;
            $resources[] = \App\Filament\Resources\ProfilResource::class;
        }

        return $resources;
    }
}
