<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropForeign(['prefecture_id']);
            $table->dropColumn('prefecture_id');
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->unsignedBigInteger('prefecture_id')->nullable()->after('nom');
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->onDelete('set null');
        });
    }
};
