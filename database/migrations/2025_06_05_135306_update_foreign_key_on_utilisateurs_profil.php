<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropForeign(['profil_id']);
            $table->foreign('profil_id')
                ->references('id')
                ->on('profils')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropForeign(['profil_id']);
            $table->foreign('profil_id')
                ->references('id')
                ->on('profils');
        });
    }
};
