<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStandingsCupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_standings_cup', function (Blueprint $table) {
            $table->string('standing_id', 100)->primary();
            $table->string('team_id', 100);
            $table->string('tour_id', 100);
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_standings_cup');
    }
}
