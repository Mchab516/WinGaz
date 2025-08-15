<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'code_client',
        'categorie',
        'adresse',
        'ville_id',
        'created_by',
        'updated_by',
        'deleted_by', // pour tracer qui a supprimé
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // --- Relations ---
    public function ville(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Ville::class, 'ville_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Utilisateur::class, 'created_by');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Utilisateur::class, 'updated_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Utilisateur::class, 'deleted_by');
    }

    public function chargementsVentes(): HasMany
    {
        return $this->hasMany(ChargementsVentes::class);
    }

    // --- Trace "qui a supprimé" pour les soft deletes ---
    protected static function booted(): void
    {
        static::deleted(function (Client $model) {
            // ne rien faire si suppression définitive
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                return;
            }

            $model->forceFill(['deleted_by' => Auth::id()])->saveQuietly();
        });
    }
}
