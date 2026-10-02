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
        if (Schema::hasTable('tbl_results')) {
            return;
        }

        Schema::create('tbl_results', function (Blueprint $table) {
            $table->string('result_id', 100)->primary();
            $table->string('match_id', 100)->nullable()->index();
            $table->string('away_team', 100);
            $table->string('home_team', 100);
            $table->integer('home_score');
            $table->integer('away_score');
            $table->integer('home_score_pen')->nullable();
            $table->integer('away_score_pen')->nullable();
            $table->string('match_stage', 100)->nullable();
            $table->timestamps();
            $table->string('tour_id', 100)->index();
            $table->string('date_played', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_results');
    }
};
