<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profil extends Model
{
    protected $table = 'profils';

    protected $fillable = [
        'libelle',
        'code_sap',
        'site',
        'nature',
        'identifiant'
    ];

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(Utilisateur::class);
    }
}
