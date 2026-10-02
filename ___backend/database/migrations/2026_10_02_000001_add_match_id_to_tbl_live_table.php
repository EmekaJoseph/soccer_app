<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Older databases (see soccerApp.sql) have no link from a live match back
     * to its scheduled match, which "end and save result" needs.
     */
    public function up(): void
    {
        if (Schema::hasColumn('tbl_live', 'match_id')) {
            return;
        }

        Schema::table('tbl_live', function (Blueprint $table) {
            $table->string('match_id', 100)->nullable()->after('live_id')->index();
        });
    }

    public function down(): void
    {
        // Kept on rollback: the column predates this migration on most installs.
    }
};
