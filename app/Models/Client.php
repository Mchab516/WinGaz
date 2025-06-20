<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'nom',
        'code_client',
        'categorie',
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
        return $this->belongsTo(related: Utilisateur::class, foreignKey: 'created_by');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(related: Utilisateur::class, foreignKey: 'updated_by');
    }

    public function chargementsVentes(): HasMany
    {
        return $this->hasMany(ChargementsVentes::class);
    }
}
