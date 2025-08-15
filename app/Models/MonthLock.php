<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthLock extends Model
{
    protected $fillable = [
        'societe',
        'annee',
        'mois',
        'locked_by',
        'locked_at',
    ];

    // (optionnel) relation vers l'utilisateur qui a verrouillé
    public function user()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }
}
