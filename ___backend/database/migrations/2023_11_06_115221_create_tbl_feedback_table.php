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
        if (Schema::hasTable('tbl_feedback')) {
            return;
        }

        Schema::create('tbl_feedback', function (Blueprint $table) {
            $table->increments('feedback_id');
            $table->string('tour_id', 100)->index();
            $table->string('name')->nullable();
            $table->text('feedbackText');
            $table->string('created_at', 100);
            $table->string('device_ip', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_feedback');
    }
};
