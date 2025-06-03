<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nom' => 'Mohammed',
            'prenom' => 'Admin',
            'email' => 'admin@wingaz.com',
            'password' => Hash::make('password123'),
            'profil_id' => 1,
        ]);
    }
}
