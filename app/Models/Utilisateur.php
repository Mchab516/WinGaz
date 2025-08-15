<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // optionnel mais utile (notifications)
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'profil_id',
    ];

    protected $hidden = [
        'password',
        'remember_token', // si la colonne existe dans ta table
    ];

    // 👉 Clé de voûte : hash auto en bcrypt à CHAQUE écriture
    protected $casts = [
        'password' => 'hashed',
        // 'email_verified_at' => 'datetime', // décommente si tu as cette colonne
    ];

    public function profil(): BelongsTo
    {
        return $this->belongsTo(Profil::class);
    }

    public function clientsCrees(): HasMany
    {
        return $this->hasMany(Client::class, 'created_by');
    }

    public function clientsModifies(): HasMany
    {
        return $this->hasMany(Client::class, 'updated_by');
    }

    public function historiqueModifications(): HasMany
    {
        return $this->hasMany(Historique_modifications::class, 'user_id');
    }
}
