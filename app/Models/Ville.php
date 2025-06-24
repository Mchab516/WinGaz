<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ville extends Model
{

    protected $fillable = ['nom'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(related: Region::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(related: Client::class);
    }

    public function centreEmplisseurs(): HasMany
    {
        return $this->hasMany(related: CentreEmplisseur::class);
    }
}
