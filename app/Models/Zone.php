<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = ['libelle'];

    public function communes(): HasMany
    {
        return $this->hasMany(Commune::class);
    }
}
