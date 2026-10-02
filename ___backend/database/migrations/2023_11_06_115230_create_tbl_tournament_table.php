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
        if (Schema::hasTable('tbl_tournament')) {
            return;
        }

        Schema::create('tbl_tournament', function (Blueprint $table) {
            $table->string('tour_id', 100)->primary();
            $table->string('tour_title');
            $table->integer('user_id')->index();
            $table->string('tour_type', 100);
            $table->string('tour_logo', 100)->nullable();
            $table->text('tour_desc')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_tournament');
    }
};
