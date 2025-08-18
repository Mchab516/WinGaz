<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Redirect;

class CustomButtonsWidget extends Widget
{
    protected static ?string $pollingInterval = null;

    protected static string $view = 'filament.admin.widgets.custom-buttons-widget';

    protected int | string | array $columnSpan = 'full';

    protected function getMaxWidth(): MaxWidth|string|null
    {
        return MaxWidth::Full;
    }

    public function goToChargement(): void
    {
        $this->redirect('/admin/chargements');
    }

    public function goToVente(): void
    {
        $this->redirect('/admin/ventes');
    }

    public function goToReporting(): void
    {
        $this->redirect('/admin/reporting');
    }
}
