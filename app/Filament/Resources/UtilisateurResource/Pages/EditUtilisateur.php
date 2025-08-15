<?php

namespace App\Filament\Resources\UtilisateurResource\Pages;

use App\Filament\Resources\UtilisateurResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUtilisateur extends EditRecord
{
    protected static string $resource = UtilisateurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Intercepte les données avant sauvegarde :
     * - si new_password est rempli, on le copie dans password (qui sera hashé via cast 'hashed').
     * - on enlève les champs temporaires pour ne pas les persister.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['new_password'])) {
            $data['password'] = $data['new_password']; // hash auto via cast du modèle
        }

        unset($data['new_password'], $data['new_password_confirmation']);

        return $data;
    }

    /**
     * Petite notif après sauvegarde, avec info si le mot de passe a changé.
     */
    protected function afterSave(): void
    {
        Notification::make()
            ->title('Utilisateur mis à jour')
            ->body($this->record->wasChanged('password') ? 'Le mot de passe a été modifié.' : null)
            ->success()
            ->send();
    }
}
