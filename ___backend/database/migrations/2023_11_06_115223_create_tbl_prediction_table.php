<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPredictionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_prediction', function (Blueprint $table) {
            $table->string('prediction_id', 100)->primary();
            $table->string('first_place', 100);
            $table->string('second_place', 100);
            $table->string('third_place', 100);
            $table->string('created_at', 100);
            $table->string('full_name', 100);
            $table->string('email', 100);
            $table->string('phone_number', 100);
            $table->string('device_ip', 100);
            $table->string('tour_id', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_prediction');
    }
}
