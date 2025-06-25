<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('chargements_ventes', function (Blueprint $table) {
            $table->string('societe')->nullable(); // ← ajout du champ société
        });
    }

    public function down(): void
    {
        Schema::table('chargements_ventes', function (Blueprint $table) {
            $table->dropColumn('societe'); // ← suppression lors du rollback
        });
    }
};
