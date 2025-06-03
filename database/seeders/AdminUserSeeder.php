<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profil = \App\Models\Profil::firstOrCreate([
            'libelle' => 'Administrateur',
            'code_sap' => 'ADMIN',
            'identifiant' => 'admin'
        ]);

        \App\Models\User::firstOrCreate(
            ['email' => 'mchab.mohammed@gmail.com'],
            [
                'nom' => 'Admin',
                'password' => bcrypt('password123'),
            ]
        );
    }
}
