<?php

namespace App\Console\Commands;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Console\Command;

class CreateFilamentUserCustom extends Command
{
    protected $signature = 'make:filament-user-custom';
    protected $description = 'Create a Filament user with custom fields';

    public function handle()
    {
        if (!Profil::exists()) {
            $this->error('Aucun profil existant. Veuillez d\'abord créer un profil.');

            if ($this->confirm('Voulez-vous créer un profil administrateur maintenant?')) {
                $profil = Profil::create([
                    'libelle' => 'Administrateur',
                    'code_sap' => 'ADMIN',
                    'identifiant' => 'admin_' . time()
                ]);
                $this->info('Profil Administrateur créé avec ID: ' . $profil->id);
            } else {
                return;
            }
        }

        $user = User::create([
            'nom' => $this->ask('Nom'),
            'prenom' => $this->ask('Prénom'),
            'email' => $this->ask('Email address'),
            'password' => bcrypt($this->secret('Password')),
            'profil_id' => $this->getProfilId()
        ]);

        $this->info("Utilisateur {$user->email} créé avec succès !");
    }

    protected function getProfilId()
    {
        $profils = Profil::all();

        foreach ($profils as $profil) {
            $this->line("[{$profil->id}] {$profil->libelle}");
        }

        return $this->ask('Entrez l\'ID du profil');
    }
}
