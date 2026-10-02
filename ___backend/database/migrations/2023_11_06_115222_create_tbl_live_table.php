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
        if (Schema::hasTable('tbl_live')) {
            return;
        }

        Schema::create('tbl_live', function (Blueprint $table) {
            $table->increments('live_id');
            $table->string('match_id', 100)->nullable()->index();
            $table->string('home_team', 100);
            $table->string('away_team', 100);
            $table->integer('home_team_score')->default(0);
            $table->integer('away_team_score')->default(0);
            $table->string('tour_id', 100)->index();
            $table->string('creator', 100);
            $table->integer('isPaused')->default(0);
            $table->string('match_stage', 100)->nullable();
            $table->integer('curr_time')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_live');
    }
};
