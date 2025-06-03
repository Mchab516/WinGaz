<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profil;

class ProfilSeeder extends Seeder
{
    public function run(): void
    {

        Profil::updateOrCreate(
            ['identifiant' => 'admin'],
            [
                'libelle' => 'Administrateur',
                'code_sap' => 'ADM001',
                'site' => 'Casablanca',
                'nature' => 'Interne',
            ]
        );
    }
}
