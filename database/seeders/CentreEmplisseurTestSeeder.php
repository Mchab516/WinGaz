<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Villes;
use App\Models\Region;
use App\Models\Utilisateur;
use App\Models\CentreEmplisseur;
use Illuminate\Support\Facades\Hash;

class CentreEmplisseurTestSeeder extends Seeder
{
    public function run(): void
    {

        $region = Region::firstOrCreate(['nom' => 'Région Test']);


        $ville = Villes::firstOrCreate(
            ['nom' => 'Ville Test'],
            ['region_id' => $region->id]
        );


        $user = Utilisateur::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Test',
                'password' => Hash::make('password'),
                'profil_id' => 1
            ]
        );


        CentreEmplisseur::firstOrCreate(
            ['code_sap' => 'SAP-TEST'],
            [
                'nom' => 'Centre Test',
                'adresse' => '123 Rue Exemple',
                'ville_id' => $ville->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );
    }
}
