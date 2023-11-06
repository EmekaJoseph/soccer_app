<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSubusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_subusers', function (Blueprint $table) {
            $table->integer('subuser_id')->primary();
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('email', 100);
            $table->string('password', 100);
            $table->string('user_id', 100);
            $table->string('created_at', 100);
            $table->string('is_active', 10)->default('1');
            $table->string('role', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_subusers');
    }
}
