<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('month_locks', function (Blueprint $table) {
            $table->id();
            $table->string('societe')->default('WINXO'); // si tu veux verrouiller par société
            $table->integer('annee');
            $table->string('mois', 2); // "01"..."12"
            $table->foreignId('locked_by')->constrained('utilisateurs'); // correspond à ton User
            $table->timestamp('locked_at');
            $table->timestamps();

            $table->unique(['societe', 'annee', 'mois']); // un lock par mois/société
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('month_locks');
    }
};
