<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ventes');
    }

    public function down(): void
    {
        // Laisse vide, on ne veut pas restaurer la table
    }
};
