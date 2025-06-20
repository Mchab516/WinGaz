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
        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->nullable();
            $table->foreignId('prefecture_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('set null'); // 👉 ajoute cette ligne
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communes');
    }
};
