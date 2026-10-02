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
        if (Schema::hasTable('tbl_subusers')) {
            return;
        }

        Schema::create('tbl_subusers', function (Blueprint $table) {
            $table->increments('subuser_id');
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password', 100);
            $table->string('user_id', 100)->index();
            $table->string('created_at', 100);
            $table->string('is_active', 10)->default('1');
            $table->string('role', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_subusers');
    }
};
