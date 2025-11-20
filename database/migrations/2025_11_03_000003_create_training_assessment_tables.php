<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        /**
         * 1. Peserta Pelatihan (Guru atau Siswa)
         * --------------------------------------
         */
        Schema::create('training_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->enum('type', ['teacher', 'student'])->index(); // guru/siswa
            $table->string('school_origin')->nullable();
            $table->string('batch')->nullable(); // angkatan / gelombang pelatihan
            $table->string('whatsapp_number', 20)->nullable();
            $table->timestamps();
        });

        /**
         * 2. Kegiatan Pelatihan Harian
         * --------------------------------------
         * Contoh: Pretest, Penjumlahan, Pengurangan, dll.
         */
        Schema::create('training_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('description', 255)->nullable();
            $table->integer('day_number')->nullable(); // Hari ke-1 s/d ke-14
            $table->timestamps();
        });

        /**
         * 3. Penilaian Harian (Instrumen Penilaian)
         * --------------------------------------
         * Menyimpan nilai per peserta per kegiatan.
         */
        Schema::create('training_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // relasi peserta
            $table->uuid('participant_id');
            $table->foreign('participant_id')
                ->references('id')
                ->on('training_participants')
                ->onDelete('cascade');

            // relasi kegiatan
            $table->uuid('activity_id');
            $table->foreign('activity_id')
                ->references('id')
                ->on('training_activities')
                ->onDelete('cascade');

            // nilai per hari
            $table->decimal('score', 5, 2)->nullable(); // 0-100
            $table->enum('attendance', ['present', 'absent'])->default('present');
            $table->text('notes')->nullable();

            // tanggal penilaian
            $table->date('assessment_date')->nullable();

            $table->timestamps();

            // mencegah duplikasi nilai untuk kombinasi yang sama
            $table->unique(['participant_id', 'activity_id', 'assessment_date'], 'unique_assessment');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_assessments');
        Schema::dropIfExists('training_activities');
        Schema::dropIfExists('training_participants');
    }
};
