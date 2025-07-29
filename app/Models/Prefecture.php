<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Region;
use App\Models\Commune;

class Prefecture extends Model

{
    protected $fillable = ['nom', 'id_region', 'id_prefectures'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'id_region');
    }


    // 🔁 Une préfecture a plusieurs communes
    public function communes(): HasMany
    {
        return $this->hasMany(Commune::class);
    }
}
