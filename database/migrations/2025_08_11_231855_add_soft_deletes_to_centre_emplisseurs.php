<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('centre_emplisseurs', function (Blueprint $table) {
            $table->softDeletes(); // deleted_at
            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('utilisateurs')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('centre_emplisseurs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('deleted_by');
            $table->dropSoftDeletes();
        });
    }
};
