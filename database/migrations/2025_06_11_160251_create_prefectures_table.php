<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prefectures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_region');
            $table->unsignedBigInteger('id_prefectures')->nullable();
            $table->string('nom'); // nom
            $table->timestamps();

            // Clé étrangère vers regions(id)
            $table->foreign('id_region')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prefectures');
    }
};
