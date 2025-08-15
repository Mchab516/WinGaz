<?php

namespace App\Filament\Resources\ChargementsVentesResource\Pages;

use App\Filament\Resources\ChargementsVentesResource;
use App\Models\ChargementsVentes;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Log;

class ReportingMensuel extends Page
{
    protected static string $resource = ChargementsVentesResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.resources.chargements-ventes-resource.pages.reporting-mensuel';
    protected static ?string $title = 'Reporting Mensuel';

    protected static ?int $navigationSort = 8;

    /**
     * Autorisation d’accès à la page – signature compatible avec Page::canAccess(array $parameters = []): bool
     */
    public static function canAccess(array $parameters = []): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Filament::auth()->user();

        Log::info('ReportingMensuel canAccess', [
            'user_id' => $user?->id,
            'email'   => $user?->email,
            'roles'   => $user?->getRoleNames()?->toArray(),
        ]);

        return $user?->hasAnyRole(['Admin', 'Comptabilité', 'Service']) ?? false;
    }

    public function mount(): void
    {
        // L’accès est déjà filtré par canAccess(); rien à faire ici.
    }

    /**
     * Chargement des données pour la vue blade
     */
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

        // Filtres simples
        if (request()->filled('annee')) {
            $query->where('annee', request('annee'));
        }

        if (request()->filled('mois')) {
            $query->where('mois', request('mois'));
        }

        // Recherche globale
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('societe', 'like', "%{$search}%")
                    ->orWhere('annee', 'like', "%{$search}%")
                    ->orWhere('mois', 'like', "%{$search}%")
                    ->orWhereHas('client', fn($q) =>
                    $q->where('code_client', 'like', "%{$search}%")
                        ->orWhere('categorie', 'like', "%{$search}%"))
                    ->orWhereHas('centreEmplisseur', fn($q) =>
                    $q->whereRaw("nom REGEXP ?", ["\\b" . preg_quote($search)]))
                    ->orWhereHas('region', fn($q) =>
                    $q->where('nom', 'like', "%{$search}%"))
                    ->orWhereHas('prefecture', fn($q) =>
                    $q->where('nom', 'like', "%{$search}%"))
                    ->orWhereHas('commune', fn($q) =>
                    $q->where('nom', 'like', "%{$search}%"))
                    ->orWhereHas('communeDecoupage', fn($q) =>
                    $q->where('nom', 'like', "%{$search}%"));
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
            if (request()->filled("min_{$quantity}")) {
                $query->where($quantity, '>=', request("min_{$quantity}"));
            }
            if (request()->filled("max_{$quantity}")) {
                $query->where($quantity, '<=', request("max_{$quantity}"));
            }
        }

        // Tri
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
            'commune',
        ], true)) {
            switch ($sort) {
                case 'centre_emplisseur':
                    $query->join('centre_emplisseurs', 'centre_emplisseurs.id', '=', 'chargements_ventes.centre_emplisseur_id')
                        ->orderBy('centre_emplisseurs.nom', $direction);
                    break;

                case 'code_client':
                case 'categorie_client':
                    $query->join('clients', 'clients.id', '=', 'chargements_ventes.client_id')
                        ->orderBy('clients.' . ($sort === 'code_client' ? 'code_client' : 'categorie'), $direction);
                    break;

                case 'code_region':
                case 'region':
                    $query->join('regions', 'regions.id', '=', 'chargements_ventes.region_id')
                        ->orderBy('regions.' . ($sort === 'code_region' ? 'id' : 'nom'), $direction);
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

            // éviter les doublons après les join
            $query->select('chargements_ventes.*');
        }

        return $query->paginate(15);
    }
}
