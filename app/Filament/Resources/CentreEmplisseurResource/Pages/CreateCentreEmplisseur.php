<?php

namespace App\Filament\Resources\CentreEmplisseurResource\Pages;

use App\Filament\Resources\CentreEmplisseurResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCentreEmplisseur extends CreateRecord
{
    protected static string $resource = CentreEmplisseurResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }
}
