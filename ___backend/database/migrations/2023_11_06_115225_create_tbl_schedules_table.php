<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_matches', function (Blueprint $table) {
            $table->string('match_id', 100)->primary();
            $table->string('tour_id', 100);
            $table->string('venue');
            $table->string('kick_off', 100);
            $table->string('home_team', 100);
            $table->string('away_team', 100);
            $table->string('match_stage', 100)->nullable();
            $table->string('created', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_matches');
    }
}
