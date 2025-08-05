<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('zones')->insert([
            ['id' => 1, 'libelle' => 'Rurale'],
            ['id' => 2, 'libelle' => 'Urbain'],
        ]);
    }
}
