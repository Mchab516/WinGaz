<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->delete();

        DB::table('clients')->insert([
            ['nom' => 'AMOUHDI GAZ', 'code_client' => '102177', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'AZOULE GAZ', 'code_client' => '101823', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'BENJELLOUN', 'code_client' => '100167', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'BERRAD', 'code_client' => '100187', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'BLEDI GAZ', 'code_client' => '101437', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'BOUZOGA GAZ', 'code_client' => '101858', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'EURO GAZ', 'code_client' => '100169', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'GALAXY GAZ', 'code_client' => '102236', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'GAZ ARBAOUA', 'code_client' => '102024', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'IGHIR GAZ', 'code_client' => '101824', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'JANA GAZ', 'code_client' => '101233', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'KNS DISTRIBUTION GAZ', 'code_client' => '101715', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'LAAFOU GAZ', 'code_client' => '101655', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'LOUJAD ENERGIE', 'code_client' => '102218', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'MAREP', 'code_client' => '101057', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'MENARA GAZ', 'code_client' => '100170', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'MILGO GAZ', 'code_client' => '100227', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'RAS GAZ', 'code_client' => '100432', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'SAHIB GAZ', 'code_client' => '100178', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'SALAMAT SERVICES', 'code_client' => '101985', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'SODISGAZ', 'code_client' => '100168', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'TAMUDA GAZ', 'code_client' => '100671', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1], // ancien doublon corrigé
            ['nom' => 'TRANSPORT BENI SMIR', 'code_client' => '100181', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
            ['nom' => 'ZEMAMRA GAZ', 'code_client' => '102228', 'categorie' => 'Indirect', 'adresse' => '', 'ville_id' => 1, 'created_by' => 1],
        ]);
    }
}
