<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProfilSeeder::class,
            SuperAdminSeeder::class,
            RegionSeeder::class,
            VilleSeeder::class, // 
            PrefectureSeeder::class,
            CommuneSeeder::class,
            CentreEmplisseurSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
