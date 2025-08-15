<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;   // ← NEW
use Illuminate\Support\Facades\Auth;           // ← NEW

class CentreEmplisseur extends Model
{
    use SoftDeletes; // ← active deleted_at

    protected $table = 'centre_emplisseurs';

    protected $fillable = [
        'nom',
        'code_sap',
        'adresse',
        'ville_id',
        'created_by',
        'updated_by',
        'deleted_by', // ← NEW (qui a supprimé)
    ];

    protected $casts = [
        'deleted_at' => 'datetime', // ← NEW (confort)
    ];

    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'created_by');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'updated_by');
    }

    public function deletedBy(): BelongsTo   // ← NEW
    {
        return $this->belongsTo(Utilisateur::class, 'deleted_by');
    }

    public function chargementsVentes(): HasMany
    {
        return $this->hasMany(ChargementsVentes::class);
    }

    // ← NEW : lors d’un soft delete, on enregistre l’utilisateur
    protected static function booted(): void
    {
        static::deleted(function (CentreEmplisseur $model) {
            // ne rien faire si suppression définitive
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                return;
            }

            $model->forceFill(['deleted_by' => Auth::id()])->saveQuietly();
        });
    }
}
