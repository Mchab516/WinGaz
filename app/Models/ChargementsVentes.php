<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Client;
use App\Models\CentreEmplisseur;
use App\Models\Prefecture;
use App\Models\Commune;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;


class ChargementsVentes extends Model
{
    protected $table = 'chargements_ventes';

    protected $fillable = [
        'societe',
        'annee',
        'mois',
        'client_id',
        'centre_emplisseur_id',
        'prefecture_id',
        'commune_id',
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

    // 🔄 Remplir automatiquement les champs created_by et updated_by
    protected static function booted(): void
    {
        static::creating(function ($record) {
            if (Auth::check()) {
                $record->created_by = Auth::id();
            }
        });

        static::updating(function ($record) {
            if (Auth::check()) {
                $record->updated_by = Auth::id();
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
        return $this->belongsTo(Prefecture::class, 'id_prefectures');
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'created_by');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'updated_by');
    }
}
