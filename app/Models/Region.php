<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Ville;

class Region extends Model
{
    protected $table = 'regions';

    protected $fillable = ['nom'];

    public function villes(): HasMany
    {
        return $this->hasMany(Ville::class);
    }
    public function prefectures(): HasMany
    {
        return $this->hasMany(Prefecture::class);
    }
}
