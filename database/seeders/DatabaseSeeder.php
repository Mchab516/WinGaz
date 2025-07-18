<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ClientSeeder::class,
            CommuneSeeder::class,
            PrefectureSeeder::class,
            ProfilSeeder::class,
            RegionSeeder::class,
            SuperAdminSeeder::class,
            VilleSeeder::class,
            CentreEmplisseurSeeder::class,

        ]);
    }
}
