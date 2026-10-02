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
        if (Schema::hasTable('tbl_players')) {
            return;
        }

        Schema::create('tbl_players', function (Blueprint $table) {
            $table->string('player_id', 50)->primary();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('image', 100)->nullable();
            $table->string('info', 50)->nullable();
            $table->string('dob', 50)->nullable();
            $table->string('tour_id', 50)->nullable()->index();
            $table->string('team_id', 50)->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_players');
    }
};
