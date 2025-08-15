<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CentreEmplisseurSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('centre_emplisseurs')->insert([
            ['nom' => 'MAGHREB GAZ LAACILATE',     'code_sap' => 'G119', 'adresse' => '', 'ville_id' => 172, 'created_by' => 1],
            ['nom' => 'MAGHREB GAZ BENSLIMANE',    'code_sap' => 'G117', 'adresse' => '', 'ville_id' => 121, 'created_by' => 1],
            ['nom' => 'AFRIQUIA GAZ MOHAMMEDIA',   'code_sap' => 'G111', 'adresse' => '', 'ville_id' => 381, 'created_by' => 1],
            ['nom' => 'AFRIQUIA GAZ BENI MELLAL',  'code_sap' => 'G112', 'adresse' => '', 'ville_id' => 115, 'created_by' => 1],
            ['nom' => 'GAZ AFRIC',                 'code_sap' => 'G116', 'adresse' => '', 'ville_id' => 228, 'created_by' => 1],
            ['nom' => 'TADLA GAZ',                 'code_sap' => 'G115', 'adresse' => '', 'ville_id' => 308, 'created_by' => 1],
            ['nom' => 'AB GAZ SOUK LARBAA',        'code_sap' => 'G125', 'adresse' => '', 'ville_id' => 526, 'created_by' => 1],
            ['nom' => 'SALAM GAZ SKHIRAT',         'code_sap' => 'G121', 'adresse' => '', 'ville_id' => 519, 'created_by' => 1],
            ['nom' => 'SALAM GAZ TANGER',          'code_sap' => 'G122', 'adresse' => '', 'ville_id' => 561, 'created_by' => 1],
            ['nom' => 'SALAM GAZ TETOUAN',         'code_sap' => 'G123', 'adresse' => '', 'ville_id' => 586, 'created_by' => 1],
            ['nom' => 'SALAM GAZ FES-MEKNES',      'code_sap' => 'G124', 'adresse' => '', 'ville_id' => 389, 'created_by' => 1],
            ['nom' => 'SALAM GAZ MARRAKECH',       'code_sap' => 'G118', 'adresse' => '', 'ville_id' => 274, 'created_by' => 1],
        ]);
    }
}
