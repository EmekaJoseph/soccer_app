<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_teams', function (Blueprint $table) {
            $table->string('team_id', 100)->primary();
            $table->string('team_name');
            $table->string('tour_id', 100);
            $table->integer('match_played')->default(0);
            $table->string('group_in', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('manager', 100)->nullable();
            $table->timestamps();
            $table->text('team_brief')->nullable();
            $table->string('team_badge', 100)->nullable();
            $table->string('team_color', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_teams');
    }
}
