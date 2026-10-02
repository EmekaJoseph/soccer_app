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
        if (Schema::hasTable('tbl_standings_cup')) {
            return;
        }

        Schema::create('tbl_standings_cup', function (Blueprint $table) {
            $table->string('standing_id', 100)->primary();
            $table->string('team_id', 100)->index();
            $table->string('tour_id', 100)->index();
            $table->string('group_in', 100)->nullable();
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('draw')->default(0);
            $table->integer('lose')->default(0);
            $table->integer('goal_diff')->default(0);
            $table->integer('points')->default(0);
            $table->timestamps();
            $table->string('extra_col', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_standings_cup');
    }
};
