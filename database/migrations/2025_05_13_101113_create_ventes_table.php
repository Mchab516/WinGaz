<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'ventes', callback: function (Blueprint $table): void {
            $table->id();
            $table->date(column: 'date_collecte');
            $table->string(column: 'taille_bouteille');
            $table->float(column: 'quantite');
            $table->string(column: 'code_commune');
            $table->foreignId(column: 'client_id')->constrained(table: 'clients');
            $table->foreignId(column: 'centre_emplisseur_id')->constrained(table: 'centre_emplisseurs');
            $table->foreignId(column: 'created_by')->constrained(table: 'utilisateurs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
