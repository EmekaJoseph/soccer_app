<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Production databases have tbl_results.match_id in utf8mb4_vi_0900_ai_ci while
     * tbl_matches.match_id is utf8mb4_0900_ai_ci, so MySQL rejects any comparison
     * between them ("Illegal mix of collations"). Give results the matches collation.
     */
    public function up(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        $collation = fn (string $table) => DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', 'match_id')
            ->value('COLLATION_NAME');

        $target = $collation('tbl_matches');
        $current = $collation('tbl_results');

        if ($target && $current && $target !== $current) {
            DB::statement("ALTER TABLE `tbl_results` MODIFY `match_id` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE {$target} NULL");
        }
    }

    public function down(): void
    {
        // Not reversed: the previous collation was accidental.
    }
};
