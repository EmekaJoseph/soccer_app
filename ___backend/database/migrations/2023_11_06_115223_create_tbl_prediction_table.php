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
        if (Schema::hasTable('tbl_prediction')) {
            return;
        }

        Schema::create('tbl_prediction', function (Blueprint $table) {
            $table->string('prediction_id', 100)->primary();
            $table->string('first_place', 100);
            $table->string('second_place', 100);
            $table->string('third_place', 100);
            $table->string('created_at', 100);
            $table->string('full_name', 100);
            $table->string('email', 100);
            $table->string('phone_number', 100);
            $table->string('device_ip', 100);
            $table->string('tour_id', 100)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_prediction');
    }
};
