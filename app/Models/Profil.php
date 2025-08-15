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
        'identifiant',
        // Flags d'accès pilotés depuis l'UI :
        'can_clients',
        'can_centres',
        'can_chargements_ventes',
        'can_reporting',
        'can_admin_menu',
    ];

    protected $casts = [
        'can_clients' => 'bool',
        'can_centres' => 'bool',
        'can_chargements_ventes' => 'bool',
        'can_reporting' => 'bool',
        'can_admin_menu' => 'bool',
    ];

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(Utilisateur::class);
    }

    /** Petit helper pour vérifier un flag : $profil->canPerm('can_reporting') */
    public function canPerm(string $key): bool
    {
        return (bool) ($this->{$key} ?? false);
    }
}
