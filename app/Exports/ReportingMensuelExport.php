<?php

namespace App\Exports;

use App\Models\ChargementsVentes;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportingMensuelExport implements FromView
{
    protected $annee;
    protected $mois;

    public function __construct($annee, $mois)
    {
        $this->annee = $annee;
        $this->mois = $mois;
    }

    public function view(): View
    {
        $records = ChargementsVentes::with([
            'client',
            'region',
            'prefecture',
            'commune',
            'communeDecoupage',
            'centreEmplisseur'
        ])
            ->when($this->annee, fn($q) => $q->where('annee', $this->annee))
            ->when($this->mois, fn($q) => $q->where('mois', $this->mois))
            ->get()
            ->map(function ($record) {
                foreach (
                    [
                        'qte_charge_3kg',
                        'qte_charge_6kg',
                        'qte_charge_9kg',
                        'qte_charge_12kg',
                        'qte_charge_35kg',
                        'qte_charge_40kg',
                        'qte_vendu_3kg',
                        'qte_vendu_6kg',
                        'qte_vendu_9kg',
                        'qte_vendu_12kg',
                        'qte_vendu_35kg',
                        'qte_vendu_40kg'
                    ] as $field
                ) {
                    $record->$field = round(($record->$field ?? 0) / 1000, 3);
                }
                return $record;
            });

        return view('exports.reporting', ['records' => $records]);
    }
}
