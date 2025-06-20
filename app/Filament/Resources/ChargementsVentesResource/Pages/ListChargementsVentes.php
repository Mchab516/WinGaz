<?php

namespace App\Filament\Resources\ChargementsVentesResource\Pages;

use App\Filament\Resources\ChargementsVentesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChargementsVentes extends ListRecords
{
    protected static string $resource = ChargementsVentesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
