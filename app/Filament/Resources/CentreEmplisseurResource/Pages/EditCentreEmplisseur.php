<?php

namespace App\Filament\Resources\CentreEmplisseurResource\Pages;

use App\Filament\Resources\CentreEmplisseurResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentreEmplisseur extends EditRecord
{
    protected static string $resource = CentreEmplisseurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
