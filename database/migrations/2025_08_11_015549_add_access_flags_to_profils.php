<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            // Ajoute-les où tu veux; "after" est optionnel
            $table->boolean('can_clients')->default(false)->after('identifiant');
            $table->boolean('can_centres')->default(false)->after('can_clients');
            $table->boolean('can_chargements_ventes')->default(false)->after('can_centres');
            $table->boolean('can_reporting')->default(false)->after('can_chargements_ventes');
            $table->boolean('can_admin_menu')->default(false)->after('can_reporting');
        });
    }

    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            $table->dropColumn([
                'can_clients',
                'can_centres',
                'can_chargements_ventes',
                'can_reporting',
                'can_admin_menu',
            ]);
        });
    }
};
