<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Prefecture;
use App\Models\Zone;

class Commune extends Model
{
    protected $fillable = [
        'nom',
        'code',
        'prefecture_id',
        'zone_id', // assure-toi que cette colonne existe dans ta base
    ];

    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
