<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string('email', 100);
            $table->string('password', 100);
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->integer('no_of_leagues')->default(0);
            $table->integer('no_of_cups')->default(0);
            $table->string('role', 100)->default('admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_users');
    }
}
