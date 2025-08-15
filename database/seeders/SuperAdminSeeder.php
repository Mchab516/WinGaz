<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        Utilisateur::create([
            'nom' => 'Chab',
            'prenom' => 'Mohammed',
            'email' => 'mchab@winxo.com',
            'password' => Hash::make('password123'),
            'profil_id' => 1, // Profil super admin
        ]);
    }
}
