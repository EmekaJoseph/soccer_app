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
        if (Schema::hasTable('tbl_teams')) {
            return;
        }

        Schema::create('tbl_teams', function (Blueprint $table) {
            $table->string('team_id', 100)->primary();
            $table->string('team_name');
            $table->string('tour_id', 100)->index();
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

    public function down(): void
    {
        Schema::dropIfExists('tbl_teams');
    }
};
