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
        Schema::create(table: 'historique_modifications', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'table');
            $table->unsignedBigInteger(column: 'id_enregistrement');
            $table->string(column: 'champ_modifie');
            $table->text(column: 'ancienne_valeur')->nullable();
            $table->text(column: 'nouvelle_valeur')->nullable();
            $table->foreignId(column: 'user_id')->constrained('utilisateurs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_modifications');
    }
};
