<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profil;

class ProfilePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Ajuste les identifiants si besoin (regarde ta colonne `identifiant`)
        $rules = [
            'admin'        => [true,  true,  true,  true,  true],   // tout
            'service_gaz'  => [true,  true,  true,  true,  false],
            'compta'       => [false, false, true,  true,  false],
            'Dsi'          => [true,  true,  true,  true,  false],  // ex. DSI
        ];

        foreach ($rules as $identifiant => [$clients, $centres, $chv, $report, $adminMenu]) {
            Profil::where('identifiant', $identifiant)->update([
                'can_clients'             => $clients,
                'can_centres'             => $centres,
                'can_chargements_ventes'  => $chv,
                'can_reporting'           => $report,
                'can_admin_menu'          => $adminMenu,
                'updated_at'              => now(),
            ]);
        }
    }
}
