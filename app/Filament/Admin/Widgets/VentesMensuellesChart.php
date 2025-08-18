<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ChargementsVentes;
use App\Support\ProfilGate as PG;
use Filament\Widgets\ChartWidget;

class VentesMensuellesChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $heading = 'Ventes mensuelles (12 derniers mois)';
    protected static ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return PG::can('can_chargements_ventes') || PG::can('can_reporting');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels = [];
        $data   = [];

        for ($i = 11; $i >= 0; $i--) {
            $d   = now()->copy()->subMonths($i);
            $y   = (int) $d->year;
            $m   = str_pad((string) $d->month, 2, '0', STR_PAD_LEFT);
            $lbl = $d->format('M Y');

            $sum = (int) (ChargementsVentes::query()
                ->where('annee', $y)
                ->where('mois', $m)
                ->selectRaw('
                    SUM(
                        qte_vendu_3kg + qte_vendu_6kg + qte_vendu_9kg +
                        qte_vendu_12kg + qte_vendu_35kg + qte_vendu_40kg
                    ) as total
                ')
                ->value('total') ?? 0);

            $labels[] = $lbl;
            $data[]   = $sum;
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label' => 'Ventes (unités)',
                    'data'  => $data,
                    'tension' => 0.3,
                ],
            ],
        ];
    }
}
