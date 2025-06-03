<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historique_modifications extends Model
{
    protected $table = 'historique_modifications';

    protected $fillable = [
        'table',
        'id_enregistrement',
        'champ_modifie',
        'ancienne_valeur',
        'nouvelle_valeur',
        'user_id'
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, foreignKey: 'user_id');
    }
}
