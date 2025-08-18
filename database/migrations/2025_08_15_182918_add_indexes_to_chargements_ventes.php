<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if ($this->indexExists('chargements_ventes', 'chargements_ventes_annee_mois_index')) {
            Schema::table('chargements_ventes', function (Blueprint $table) {
                $table->dropIndex('chargements_ventes_annee_mois_index');
            });
        }
    }

    public function down(): void
    {
        if (! $this->indexExists('chargements_ventes', 'chargements_ventes_annee_mois_index')) {
            Schema::table('chargements_ventes', function (Blueprint $table) {
                $table->index(['annee', 'mois'], 'chargements_ventes_annee_mois_index');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $dbName = DB::getDatabaseName();
        $result = DB::select("
            SELECT COUNT(*) AS count
            FROM information_schema.statistics
            WHERE table_schema = ? AND table_name = ? AND index_name = ?
        ", [$dbName, $table, $index]);

        return $result[0]->count > 0;
    }
};
