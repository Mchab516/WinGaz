<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\CentreEmplisseur;
use App\Models\Prefecture;
use App\Models\Commune;
use App\Models\Region;
use App\Models\Utilisateur;

class ChargementsVentes extends Model
{
    protected $table = 'chargements_ventes';

    protected $fillable = [
        'societe',
        'annee',
        'mois',
        'client_id',
        'centre_emplisseur_id',
        'region_id',
        'prefecture_id',
        'commune_id',
        'commune_decoupage_id',
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
        'created_by',
        'updated_by',
    ];

    protected $with = [
        'prefecture',
        'commune',
        'communeDecoupage',
        'centreEmplisseur',
        'client',
        'region',
    ];

    /**
     * Boot method pour remplir automatiquement les champs.
     */
    protected static function booted(): void
    {
        static::creating(function ($record) {
            if (Auth::check()) {
                $record->created_by = Auth::id();
            }

            // Affectation automatique de region_id à partir de la préfecture
            if ($record->prefecture_id && empty($record->region_id)) {
                $prefecture = Prefecture::find($record->prefecture_id);
                if ($prefecture) {
                    $record->region_id = $prefecture->id_region;
                }
            }

            // Affectation automatique de commune_decoupage_id si non défini
            if (empty($record->commune_decoupage_id) && !empty($record->commune_id)) {
                $record->commune_decoupage_id = $record->commune_id;
            }
        });

        static::updating(function ($record) {
            if (Auth::check()) {
                $record->updated_by = Auth::id();
            }

            if ($record->isDirty('prefecture_id')) {
                $prefecture = Prefecture::find($record->prefecture_id);
                if ($prefecture) {
                    $record->region_id = $prefecture->id_region;
                }
            }

            if (
                $record->isDirty('commune_id') &&
                (empty($record->commune_decoupage_id) || $record->commune_decoupage_id === null)
            ) {
                $record->commune_decoupage_id = $record->commune_id;
            }
        });
    }

    // 🔗 Relations

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function centreEmplisseur(): BelongsTo
    {
        return $this->belongsTo(CentreEmplisseur::class);
    }

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_id');
    }

    public function communeDecoupage(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_decoupage_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'created_by');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'updated_by');
    }

    // Accessor pour affichage
    public function getIdPrefectureDisplayAttribute()
    {
        return $this->prefecture?->id_prefectures ?? '—';
    }
}
