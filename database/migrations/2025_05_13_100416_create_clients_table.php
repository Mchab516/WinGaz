<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code_client')->unique();
            $table->string('categorie');
            $table->string('adresse')->nullable();
            $table->foreignId('ville_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
