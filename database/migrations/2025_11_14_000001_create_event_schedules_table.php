<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_batchs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            /** Tahun dan Tahap EVENT */
            $table->string('event_year');   // contoh: "2025"
            $table->string('event_phase');  // contoh: "Tahap 1", "Tahap 2"

        });

        Schema::create('event_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // assign to a user (guru / pelatih) — lebih fleksibel daripada teacher_events only
            $table->uuid('user_id')->nullable()->index();
            $table->uuid('event_batch_id');   // contoh: "2025"

            /** Jadwal event per occurrence */
            $table->date('date')->nullable();
            $table->string('day_of_week')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('place')->nullable();
            $table->string('agenda')->nullable();

            $table->string('status')->default('Scheduled');
            // bisa "Scheduled", "Completed", "Cancelled", atau custom

            $table->timestamps();

            // foreign key ke users
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            // foreign key ke event_batchs
            $table->foreign('event_batch_id')
                ->references('id')->on('event_batchs')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
