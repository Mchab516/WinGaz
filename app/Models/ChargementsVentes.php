<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes; // ← NEW
use App\Models\Client;
use App\Models\CentreEmplisseur;
use App\Models\Prefecture;
use App\Models\Commune;
use App\Models\Region;
use App\Models\Utilisateur;
use App\Models\MonthLock;

class ChargementsVentes extends Model
{
    use SoftDeletes; // ← NEW

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
        'deleted_by', // ← NEW
    ];

    protected $casts = [
        'deleted_at' => 'datetime', // ← NEW
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
     * Boot: remplir created_by/updated_by et gérer deleted_by (soft delete)
     */
    protected static function booted(): void
    {
        static::creating(function ($record) {
            if (Auth::check()) {
                $record->created_by = Auth::id();
                $record->updated_by = Auth::id(); // ← NEW: set aussi à la création
            }

            // region_id depuis la préfecture
            if ($record->prefecture_id && empty($record->region_id)) {
                if ($prefecture = Prefecture::find($record->prefecture_id)) {
                    $record->region_id = $prefecture->id_region;
                }
            }

            // commune_decoupage_id par défaut
            if (empty($record->commune_decoupage_id) && ! empty($record->commune_id)) {
                $record->commune_decoupage_id = $record->commune_id;
            }
        });

        static::updating(function ($record) {
            if (Auth::check()) {
                $record->updated_by = Auth::id();
            }

            if ($record->isDirty('prefecture_id')) {
                if ($prefecture = Prefecture::find($record->prefecture_id)) {
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

        // ← NEW: tracer qui supprime (soft delete)
        static::deleted(function ($record) {
            // si suppression définitive, ne rien faire
            if (method_exists($record, 'isForceDeleting') && $record->isForceDeleting()) {
                return;
            }
            $record->forceFill(['deleted_by' => Auth::id()])->saveQuietly();
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
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'deleted_by');
    } // ← NEW

    // Accessor
    public function getIdPrefectureDisplayAttribute()
    {
        return $this->prefecture?->id_prefectures ?? '—';
    }

    public function isLocked(): bool
    {
        return MonthLock::where('societe', $this->societe ?? 'WINXO')
            ->where('annee', $this->annee)
            ->where('mois',  $this->mois)
            ->exists();
    }
}
