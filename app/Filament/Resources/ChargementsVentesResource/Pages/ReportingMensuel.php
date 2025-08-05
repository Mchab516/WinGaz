<?php

namespace App\Filament\Resources\ChargementsVentesResource\Pages;

use App\Filament\Resources\ChargementsVentesResource;
use App\Models\ChargementsVentes;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class ReportingMensuel extends Page
{
    protected static string $resource = ChargementsVentesResource::class;
    protected static string $view = 'filament.resources.chargements-ventes-resource.pages.reporting-mensuel';
    protected static ?string $title = 'Reporting Mensuel';

    // ✅ Vérification d'accès à la page
    public function mount(): void
    {
        if (! Auth::check() || ! in_array(Auth::user()->profil_id, [1, 3])) {
            throw new AuthorizationException();
        }
    }

    public function getRecordsProperty()
    {
        $query = ChargementsVentes::with([
            'client',
            'centreEmplisseur',
            'region',
            'prefecture',
            'commune',
            'communeDecoupage',
            'createur',
            'modificateur',
        ]);

        // Filtres classiques
        if (request()->filled('annee')) {
            $query->where('annee', request('annee'));
        }

        if (request()->filled('mois')) {
            $query->where('mois', request('mois'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('societe', 'like', "%$search%")
                    ->orWhere('annee', 'like', "%$search%")
                    ->orWhere('mois', 'like', "%$search%")
                    ->orWhereHas('client', fn($q) =>
                    $q->where('code_client', 'like', "%$search%")
                        ->orWhere('categorie', 'like', "%$search%"))
                    ->orWhereHas(
                        'centreEmplisseur',
                        fn($q) =>
                        $q->whereRaw("nom REGEXP ?", ["\\b" . preg_quote($search)])
                    )
                    ->orWhereHas('region', fn($q) =>
                    $q->where('nom', 'like', "%$search%"))
                    ->orWhereHas('prefecture', fn($q) =>
                    $q->where('nom', 'like', "%$search%"))
                    ->orWhereHas('commune', fn($q) =>
                    $q->where('nom', 'like', "%$search%"))
                    ->orWhereHas('communeDecoupage', fn($q) =>
                    $q->where('nom', 'like', "%$search%"));
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
            if (request()->filled("min_$quantity")) {
                $query->where($quantity, '>=', request("min_$quantity"));
            }
            if (request()->filled("max_$quantity")) {
                $query->where($quantity, '<=', request("max_$quantity"));
            }
        }

        // Gestion du tri
        $sort = request('sort');
        $direction = request('direction', 'asc');

        if ($sort && in_array($sort, [
            'societe',
            'annee',
            'mois',
            'centre_emplisseur',
            'code_client',
            'categorie_client',
            'code_region',
            'region',
            'prefecture',
            'commune_decoupage',
            'commune'
        ])) {
            switch ($sort) {
                case 'centre_emplisseur':
                    $query->join('centre_emplisseurs', 'centre_emplisseurs.id', '=', 'chargements_ventes.centre_emplisseur_id')
                        ->orderBy('centre_emplisseurs.nom', $direction);
                    break;
                case 'code_client':
                case 'categorie_client':
                    $query->join('clients', 'clients.id', '=', 'chargements_ventes.client_id')
                        ->orderBy("clients." . ($sort == 'code_client' ? 'code_client' : 'categorie'), $direction);
                    break;
                case 'code_region':
                case 'region':
                    $query->join('regions', 'regions.id', '=', 'chargements_ventes.region_id')
                        ->orderBy("regions." . ($sort == 'code_region' ? 'id' : 'nom'), $direction);
                    break;
                case 'prefecture':
                    $query->join('prefectures', 'prefectures.id', '=', 'chargements_ventes.prefecture_id')
                        ->orderBy('prefectures.nom', $direction);
                    break;
                case 'commune_decoupage':
                    $query->join('communes as cdec', 'cdec.id', '=', 'chargements_ventes.commune_decoupage_id')
                        ->orderBy('cdec.nom', $direction);
                    break;
                case 'commune':
                    $query->join('communes', 'communes.id', '=', 'chargements_ventes.commune_id')
                        ->orderBy('communes.nom', $direction);
                    break;
                default:
                    $query->orderBy($sort, $direction);
            }



            // Important : éviter doublons
            $query->select('chargements_ventes.*');
        }


        return $query->paginate(15);
    }
}
