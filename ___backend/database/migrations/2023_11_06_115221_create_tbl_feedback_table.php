<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_feedback', function (Blueprint $table) {
            $table->integer('feedback_id')->primary();
            $table->string('tour_id', 100);
            $table->string('name')->nullable();
            $table->text('feedbackText');
            $table->string('created_at', 100);
            $table->string('device_ip', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_feedback');
    }
}
