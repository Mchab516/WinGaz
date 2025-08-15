<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->softDeletes(); // deleted_at
            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('utilisateurs') // table des users personnalisée
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('deleted_by');
            $table->dropSoftDeletes();
        });
    }
};
