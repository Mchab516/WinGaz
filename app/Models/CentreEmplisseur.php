<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentreEmplisseur extends Model
{
    protected $table = 'centre_emplisseurs';

    protected $fillable = [
        'nom',
        'code_sap',
        'adresse',
        'ville_id',
        'created_by',
        'updated_by'
    ];

    public function ville(): BelongsTo
    {
        return $this->belongsTo(Villes::class);
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, foreignKey: 'created_by');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, foreignKey: 'updated_by');
    }

    public function chargementsVentes(): HasMany
    {
        return $this->hasMany(ChargementsVentes::class);
    }
}
