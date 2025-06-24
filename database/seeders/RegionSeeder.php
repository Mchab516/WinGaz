<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regions')->insert([
            ['id' => 1,  'nom' => 'TANGER-TÉTOUAN-AL HOCEÏMA'],
            ['id' => 2,  'nom' => "L'ORIENTAL"],
            ['id' => 3,  'nom' => 'FÈS- MEKNÈS'],
            ['id' => 4,  'nom' => 'RABAT-SALÉ-KÉNITRA'],
            ['id' => 5,  'nom' => 'BÉNI-MELLAL-KHÉNIFRA'],
            ['id' => 6,  'nom' => 'CASABLANCA-SETTAT'],
            ['id' => 7,  'nom' => 'MARRAKECH-SAFI'],
            ['id' => 8,  'nom' => 'DRÂA-TAFILALET'],
            ['id' => 9,  'nom' => 'SOUSS-MASSA'],
            ['id' => 10, 'nom' => 'GUELMIM-OUED NOUN'],
            ['id' => 11, 'nom' => 'LAÂYOUNE-SAKIA EL HAMRA'],
            ['id' => 12, 'nom' => 'DAKHLA-OUED ED-DAHAB'],
        ]);
    }
}
