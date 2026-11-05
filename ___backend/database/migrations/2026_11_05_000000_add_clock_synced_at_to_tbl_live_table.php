<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * When curr_time was last set, so the server can work out the match minute
     * by itself even if the scorer's phone is locked or offline.
     */
    public function up(): void
    {
        if (Schema::hasColumn('tbl_live', 'clock_synced_at')) {
            return;
        }

        Schema::table('tbl_live', function (Blueprint $table) {
            $table->timestamp('clock_synced_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_live', function (Blueprint $table) {
            $table->dropColumn('clock_synced_at');
        });
    }
};
