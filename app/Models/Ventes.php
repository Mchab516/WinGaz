<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ventes extends Model
{
    protected $table = 'ventes';

    protected $fillable = [
        'date_collecte',
        'taille_bouteille',
        'quantite',
        'code_commune',
        'client_id',
        'centre_emplisseur_id',
        'created_by'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(related: Client::class);
    }

    public function centreEmplisseur(): BelongsTo
    {
        return $this->belongsTo(related: Centre_emplisseurs::class);
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(related: Utilisateur::class, foreignKey: 'created_by');
    }
}
