<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chargements extends Model
{
    protected $table = 'chargements';

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
        return $this->belongsTo(Client::class);
    }

    public function centreEmplisseur(): BelongsTo
    {
        return $this->belongsTo(related: Centre_emplisseurs::class);
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(utilisateur::class, 'created_by');
    }
}
