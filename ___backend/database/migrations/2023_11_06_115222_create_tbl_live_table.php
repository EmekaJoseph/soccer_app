<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblLiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_live', function (Blueprint $table) {
            $table->integer('live_id')->primary();
            $table->string('home_team', 100);
            $table->string('away_team', 100);
            $table->integer('home_team_score')->default(0);
            $table->integer('away_team_score')->default(0);
            $table->string('tour_id', 100);
            $table->string('creator', 100);
            $table->integer('isPaused')->default(0);
            $table->string('match_stage', 100)->nullable();
            $table->integer('curr_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_live');
    }
}
