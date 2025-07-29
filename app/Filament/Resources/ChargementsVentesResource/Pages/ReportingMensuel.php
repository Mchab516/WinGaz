<?php

namespace App\Filament\Resources\ChargementsVentesResource\Pages;

use App\Filament\Resources\ChargementsVentesResource;
use App\Models\ChargementsVentes;
use Filament\Resources\Pages\Page;

class ReportingMensuel extends Page
{
    protected static string $resource = ChargementsVentesResource::class;
    protected static string $view = 'filament.resources.chargements-ventes-resource.pages.reporting-mensuel';
    protected static ?string $title = 'Reporting Mensuel';

    public $records;

    public function mount()
    {
        $this->records = ChargementsVentes::with([
            'client',
            'centreEmplisseur',
            'region',
            'prefecture',
            'commune',
            'communeDecoupage',
            'createur',
            'modificateur',
        ])->get();
    }
}
