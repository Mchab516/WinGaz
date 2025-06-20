<?php

namespace App\Filament\Resources\ChargementsVentesResource\Pages;

use App\Filament\Resources\ChargementsVentesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChargements extends EditRecord
{
    protected static string $resource = ChargementsVentesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
