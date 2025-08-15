<?php

namespace App\Filament\Resources\ChargementsVentesResource\Pages;

use App\Filament\Resources\ChargementsVentesResource;
use App\Models\User; // ✅ pour typer $user
use App\Support\MonthLocker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateChargementsVentes extends CreateRecord
{
    protected static string $resource = ChargementsVentesResource::class;

    protected function beforeCreate(): void
    {
        /** @var User|null $user */   // ✅ Intelephense comprend que c’est ton User (HasRoles)
        $user = Auth::user();

        if ($user?->hasAnyRole(['Admin', 'Comptabilité'])) {
            return; // autorisés
        }

        $data  = $this->form->getState();
        $annee = $data['annee'] ?? null;
        $mois  = $data['mois']  ?? null;

        if ($annee && $mois && MonthLocker::isLocked('WINXO', $annee, $mois)) {
            Notification::make()
                ->title('Mois clôturé')
                ->body("La saisie pour $annee-$mois est bloquée (clôture).")
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
