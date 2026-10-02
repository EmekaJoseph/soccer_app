<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skipped when the table already exists: production databases were created
     * from an SQL dump, so their migrations table does not list this migration.
     */
    public function up(): void
    {
        if (Schema::hasTable('tbl_matches')) {
            return;
        }

        Schema::create('tbl_matches', function (Blueprint $table) {
            $table->string('match_id', 100)->primary();
            $table->string('tour_id', 100)->index();
            $table->string('venue');
            $table->string('kick_off', 100);
            $table->string('home_team', 100);
            $table->string('away_team', 100);
            $table->string('match_stage', 100)->nullable();
            $table->string('created', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_matches');
    }
};
