<?php

namespace App\Models;


use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRoles;

    protected $guard_name = 'web';

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'profil_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function profil()
    {
        return $this->belongsTo(Profil::class);
    }

    /**
     * Accessor utilisé par Filament pour le nom complet.
     */
    public function getNameAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}
