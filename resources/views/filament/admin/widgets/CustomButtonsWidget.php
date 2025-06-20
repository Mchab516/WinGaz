<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Redirect;

class CustomButtonsWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.custom-buttons-widget';

    // Redirections au clic sur les boutons
    public function goToChargementsVentes()
    {
        return Redirect::to(route('filament.admin.resources.chargements-ventes.index'));
    }


    public function goToReporting()
    {
        return Redirect::to(route('filament.admin.resources.reportings.index'));
    }
}
