<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            // Vérifie si la colonne existe avant de la modifier
            if (Schema::hasColumn('regions', 'prefecture_id')) {
                // Supprimer la contrainte si elle existe
                try {
                    $table->dropForeign(['prefecture_id']);
                } catch (\Exception $e) {
                    // La foreign key n'existe pas, ignorer l'erreur
                }

                $table->dropColumn('prefecture_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            if (!Schema::hasColumn('regions', 'prefecture_id')) {
                $table->unsignedBigInteger('prefecture_id')->nullable()->after('nom');
                $table->foreign('prefecture_id')->references('id')->on('prefectures')->onDelete('set null');
            }
        });
    }
};
