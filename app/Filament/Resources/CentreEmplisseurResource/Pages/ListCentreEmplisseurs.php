<?php

namespace App\Filament\Resources\CentreEmplisseurResource\Pages;

use App\Filament\Resources\CentreEmplisseurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentreEmplisseurs extends ListRecords
{
    protected static string $resource = CentreEmplisseurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Gestion des centres emplisseurs';
    }

    public function getBreadcrumb(): string
    {
        return 'Gestion des centres emplisseurs';
    }
}
