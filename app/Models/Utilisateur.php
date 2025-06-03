<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Utilisateur extends Authenticatable
{
    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'profil_id'
    ];

    protected $hidden = ['mot_de_passe'];

    public function profil(): BelongsTo
    {
        return $this->belongsTo(related: Profil::class);
    }

    public function clientsCrees(): HasMany
    {
        return $this->hasMany(related: Client::class, foreignKey: 'created_by');
    }

    public function clientsModifies(): HasMany
    {
        return $this->hasMany(related: Client::class, foreignKey: 'updated_by');
    }

    public function historiqueModifications(): HasMany
    {
        return $this->hasMany(related: Historique_modifications::class, foreignKey: 'user_id');
    }
}
