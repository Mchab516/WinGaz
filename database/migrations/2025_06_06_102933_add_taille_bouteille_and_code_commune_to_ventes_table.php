<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ventes', function (Blueprint $table) {
            // Supprime ou commente les lignes suivantes si les colonnes existent déjà
            // $table->string('taille_bouteille')->nullable();
            // $table->string('code_commune')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            //
        });
    }
};
