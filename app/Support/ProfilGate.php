<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class ProfilGate
{
    public static function can(string $flag): bool
    {
        $p = Auth::user()?->profil;
        return (bool) ($p?->$flag ?? false);
    }
}
