<?php

namespace App\Filament\Resources\UtilisateurResource\Pages;

use App\Filament\Resources\UtilisateurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUtilisateurs extends ListRecords
{
    protected static string $resource = UtilisateurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Gestion des utilisateurs';
    }

    public function getBreadcrumb(): string
    {
        return 'Gestion des utilisateurs';
    }
}
