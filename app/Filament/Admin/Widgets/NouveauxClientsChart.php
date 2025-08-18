<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Client;
use App\Support\ProfilGate as PG;
use Filament\Widgets\ChartWidget;

class NouveauxClientsChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $heading = 'Nouveaux clients (12 derniers mois)';
    protected static ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return PG::can('can_clients') || PG::can('can_reporting');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $labels = [];
        $data   = [];

        for ($i = 11; $i >= 0; $i--) {
            $d   = now()->copy()->subMonths($i);
            $y   = (int) $d->year;
            $m   = (int) $d->month;
            $lbl = $d->format('M Y');

            $count = Client::whereYear('created_at', $y)->whereMonth('created_at', $m)->count();

            $labels[] = $lbl;
            $data[]   = $count;
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label' => 'Clients',
                    'data'  => $data,
                ],
            ],
        ];
    }
}
