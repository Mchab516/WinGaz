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
        Schema::create(table: 'profils', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'libelle');
            $table->string(column: 'code_sap');
            $table->string(column: 'site')->nullable();
            $table->string(column: 'nature')->nullable();
            $table->string(column: 'identifiant')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
