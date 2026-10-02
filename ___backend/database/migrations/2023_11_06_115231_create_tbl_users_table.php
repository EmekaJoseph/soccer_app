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
        if (Schema::hasTable('tbl_users')) {
            return;
        }

        Schema::create('tbl_users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->integer('no_of_leagues')->default(0);
            $table->integer('no_of_cups')->default(0);
            $table->string('role', 100)->default('admin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};
