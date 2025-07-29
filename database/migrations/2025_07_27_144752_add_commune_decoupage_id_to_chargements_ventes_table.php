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
        Schema::table('chargements_ventes', function (Blueprint $table) {
            $table->unsignedBigInteger('commune_decoupage_id')->nullable()->after('commune_id');

            $table->foreign('commune_decoupage_id')
                ->references('id')
                ->on('communes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chargements_ventes', function (Blueprint $table) {
            $table->dropForeign(['commune_decoupage_id']);
            $table->dropColumn('commune_decoupage_id');
        });
    }
};
