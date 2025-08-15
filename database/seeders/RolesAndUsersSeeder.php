<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RolesAndUsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('utilisateurs')->insert([
            [
                'email' => 'sbenamar@winxo.com',
                'nom' => 'Service',
                'prenom' => 'Gaz',
                'password' => Hash::make('Gaz@2025'),
                'profil_id' => 2, // id du profil Service Gaz
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'mmoukrim@winxo.com',
                'nom' => 'Comptabilité',
                'prenom' => 'Winxo',
                'password' => Hash::make('Compta@2025'),
                'profil_id' => 3, // id du profil Comptabilité
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
