<?php

namespace App\Filament\Resources\ChargementsVentesResource\Pages;

use App\Filament\Resources\ChargementsVentesResource;
use App\Models\User; // ✅
use App\Support\MonthLocker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditChargementsVentes extends EditRecord
{
    protected static string $resource = ChargementsVentesResource::class;

    protected function beforeSave(): void
    {
        /** @var User|null $user */   // ✅
        $user = Auth::user();

        if ($user?->hasAnyRole(['Admin', 'Comptabilité'])) {
            return; // autorisés
        }

        $record = $this->getRecord();

        if (MonthLocker::isLocked('WINXO', $record->annee, $record->mois)) {
            Notification::make()
                ->title('Mois clôturé')
                ->body("La modification pour {$record->annee}-{$record->mois} est bloquée (clôture).")
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
