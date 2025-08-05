<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profil;

class ProfilSeeder extends Seeder
{
    public function run(): void
    {
        Profil::updateOrCreate(
            ['id' => 1],
            [
                'identifiant' => 'admin',
                'libelle' => 'Administrateur',
                'code_sap' => 'ADM001',
                'site' => 'Casablanca',
                'nature' => 'Interne',
            ]
        );

        Profil::updateOrCreate(
            ['id' => 2],
            [
                'identifiant' => 'service_gaz',
                'libelle' => 'Service Gaz',
                'code_sap' => 'SRV001',
                'site' => 'Casablanca',
                'nature' => 'Interne',
            ]
        );

        Profil::updateOrCreate(
            ['id' => 3],
            [
                'identifiant' => 'compta',
                'libelle' => 'Comptabilité',
                'code_sap' => 'CMP001',
                'site' => 'Casablanca',
                'nature' => 'Interne',
            ]
        );
    }
}
