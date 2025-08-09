<?php

namespace App\Observers;

use App\Models\ChargementsVentes;
use App\Support\MonthLocker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;


class ChargementsVentesObserver
{
    /**
     * Vrai si l’opération doit être bloquée (mois clôturé et utilisateur non autorisé)
     */
    protected function mustBlock(ChargementsVentes $model): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return true;
        }

        // L’IDE sait maintenant que $user est App\Models\User
        if ($user->hasAnyRole(['Admin', 'Comptabilité'])) {
            return false;
        }

        $annee = (int) $model->annee;
        $mois  = str_pad((string) $model->mois, 2, '0', STR_PAD_LEFT);

        return MonthLocker::isLocked('WINXO', $annee, $mois);
    }



    public function creating(ChargementsVentes $model): void
    {
        if ($this->mustBlock($model)) {
            throw ValidationException::withMessages([
                'mois' => 'Le mois est clôturé : création bloquée.',
            ]);
        }

        if ($user = Auth::user()) {
            $model->created_by = $user->id;
            $model->updated_by = $user->id;
        }
    }

    public function updating(ChargementsVentes $model): void
    {
        if ($this->mustBlock($model)) {
            throw ValidationException::withMessages([
                'mois' => 'Le mois est clôturé : modification bloquée.',
            ]);
        }

        if ($user = Auth::user()) {
            $model->updated_by = $user->id;
        }
    }

    public function deleting(ChargementsVentes $model): void
    {
        if ($this->mustBlock($model)) {
            throw ValidationException::withMessages([
                'mois' => 'Le mois est clôturé : suppression bloquée.',
            ]);
        }
    }
}
