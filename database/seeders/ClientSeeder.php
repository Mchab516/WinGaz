<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Villes;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {

        $ville = Villes::firstOrCreate(
            ['nom' => 'Casablanca'],
            ['region_id' => 1]
        );


        $user = Utilisateur::firstOrCreate(
            ['email' => 'admin@client.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Client',
                'password' => Hash::make('password'),
                'profil_id' => 1,
            ]
        );


        Client::firstOrCreate(
            ['code_client' => 'CLT001'],
            [
                'nom' => 'Client Test',
                'categorie' => 'Silver',
                'adresse' => '123 Rue Client',
                'ville_id' => $ville->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );
    }
}
