<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_results', function (Blueprint $table) {
            $table->string('result_id', 100)->primary();
            $table->string('away_team', 100);
            $table->string('home_team', 100);
            $table->string('home_score', 100);
            $table->string('away_score', 100);
            $table->integer('home_score_pen')->nullable();
            $table->integer('away_score_pen')->nullable();
            $table->string('match_stage', 100)->nullable();
            $table->timestamps();
            $table->string('tour_id', 100);
            $table->string('date_played', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_results');
    }
}
