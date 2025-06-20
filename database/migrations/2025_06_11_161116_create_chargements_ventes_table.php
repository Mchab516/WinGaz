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
        Schema::create('chargements_ventes', function (Blueprint $table) {
            $table->id();

            $table->string('annee');
            $table->string('mois');

            $table->string('code_commune')->nullable();
            $table->string('type_operation')->nullable();

            // Quantités chargées
            $table->integer('qte_charge_3kg')->nullable();
            $table->integer('qte_charge_6kg')->nullable();
            $table->integer('qte_charge_9kg')->nullable();
            $table->integer('qte_charge_12kg')->nullable();
            $table->integer('qte_charge_35kg')->nullable();
            $table->integer('qte_charge_40kg')->nullable();

            // Quantités vendues
            $table->integer('qte_vendu_3kg')->nullable();
            $table->integer('qte_vendu_6kg')->nullable();
            $table->integer('qte_vendu_9kg')->nullable();
            $table->integer('qte_vendu_12kg')->nullable();
            $table->integer('qte_vendu_35kg')->nullable();
            $table->integer('qte_vendu_40kg')->nullable();

            // Foreign keys
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('centre_emplisseur_id')->nullable()->constrained('centre_emplisseurs')->nullOnDelete();
            $table->foreignId('prefecture_id')->nullable()->constrained('prefectures')->nullOnDelete();
            $table->foreignId('commune_id')->nullable()->constrained('communes')->nullOnDelete();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chargements_ventes');
    }
};
