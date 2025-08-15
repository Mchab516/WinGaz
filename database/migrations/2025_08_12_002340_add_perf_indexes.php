<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('chargements_ventes', function (Blueprint $table) {
            $table->index(['annee', 'mois']);
            $table->index(['societe', 'annee', 'mois']);
            $table->index('client_id');
            $table->index('centre_emplisseur_id');
            $table->index('region_id');
            $table->index('prefecture_id');
            $table->index('commune_id');
            $table->index('commune_decoupage_id');
            $table->index('created_at');
            $table->index('deleted_at');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->index('ville_id');
            $table->index('created_at');
            $table->index('deleted_at');
        });

        Schema::table('centre_emplisseurs', function (Blueprint $table) {
            $table->index('ville_id');
            $table->index('created_at');
            $table->index('deleted_at');
        });

        // Si tu utilises month_locks pour les verrous
        if (Schema::hasTable('month_locks')) {
            Schema::table('month_locks', function (Blueprint $table) {
                $table->index(['societe', 'annee', 'mois']);
            });
        }
    }

    public function down(): void
    {
        // Optionnel: retirer les index si besoin
    }
};
