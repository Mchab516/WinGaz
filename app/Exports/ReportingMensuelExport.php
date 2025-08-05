<?php

namespace App\Exports;

use App\Models\ChargementsVentes;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class ReportingMensuelExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = ChargementsVentes::with([
            'client',
            'region',
            'prefecture',
            'commune',
            'communeDecoupage',
            'centreEmplisseur',
        ]);

        if ($this->request->filled('annee')) {
            $query->where('annee', $this->request->input('annee'));
        }

        if ($this->request->filled('mois')) {
            $query->where('mois', $this->request->input('mois'));
        }

        if ($this->request->filled('search')) {
            $search = $this->request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('societe', 'like', "%$search%")
                    ->orWhere('annee', 'like', "%$search%")
                    ->orWhere('mois', 'like', "%$search%")
                    ->orWhereHas('client', fn($sub) => $sub->where('code_client', 'like', "%$search%")
                        ->orWhere('categorie', 'like', "%$search%"))
                    ->orWhereHas(
                        'centreEmplisseur',
                        fn($sub) =>
                        $sub->whereRaw("nom REGEXP ?", ["\\b" . preg_quote($search)])
                    )

                    ->orWhereHas('region', fn($sub) => $sub->where('nom', 'like', "%$search%"))
                    ->orWhereHas('prefecture', fn($sub) => $sub->where('nom', 'like', "%$search%"))
                    ->orWhereHas('commune', fn($sub) => $sub->where('nom', 'like', "%$search%"))
                    ->orWhereHas('communeDecoupage', fn($sub) => $sub->where('nom', 'like', "%$search%"));
            });
        }

        // Filtres numériques
        $quantities = [
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
            'qte_vendu_40kg',
        ];

        foreach ($quantities as $quantity) {
            if ($this->request->filled("min_$quantity")) {
                $query->where($quantity, '>=', $this->request->input("min_$quantity"));
            }
            if ($this->request->filled("max_$quantity")) {
                $query->where($quantity, '<=', $this->request->input("max_$quantity"));
            }
        }

        $records = $query->get()->map(function ($record) {
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
                    'qte_vendu_40kg',
                ] as $field
            ) {
                $record->$field = round(($record->$field ?? 0) / 1000, 3);
            }
            return $record;
        });

        return view('exports.reporting', ['records' => $records]);
    }
}
