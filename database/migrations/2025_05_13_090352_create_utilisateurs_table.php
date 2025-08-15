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
        Schema::create(table: 'utilisateurs', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'nom');
            $table->string(column: 'prenom');
            $table->string(column: 'email')->unique();
            $table->string(column: 'password');
            $table->foreignId(column: 'profil_id')->constrained(table: 'profils');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
