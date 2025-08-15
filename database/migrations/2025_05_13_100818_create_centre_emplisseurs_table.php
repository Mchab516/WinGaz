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
        Schema::create(table: 'centre_emplisseurs', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'nom');
            $table->string(column: 'code_sap')->unique();
            $table->string(column: 'adresse');
            $table->foreignId(column: 'ville_id')->constrained(table: 'villes');
            $table->foreignId(column: 'created_by')
                ->constrained(table: 'utilisateurs')
                ->onDelete('cascade');

            $table->foreignId(column: 'updated_by')
                ->nullable()
                ->constrained(table: 'utilisateurs')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centre_emplisseurs');
    }
};
