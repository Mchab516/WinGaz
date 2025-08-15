<?php

namespace App\Support;

use App\Models\MonthLock;

class MonthLocker
{
    public static function isLocked(string $societe, int $annee, string $mois): bool
    {
        return MonthLock::where(compact('societe', 'annee', 'mois'))->exists();
    }
}
