<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * TABEL SUBJECTS
         */
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        /**
         * TABEL RELASI ANTARA GURU LES GASING DAN STUDENT COURSE
         */
        Schema::create('teacher_student_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('teacher_id');
            $table->uuid('student_course_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_course_id')->references('id')->on('student_courses')->onDelete('cascade');
            $table->unique(['teacher_id', 'student_course_id'], 'uniq_teacher_student');
        });

        /**
         * TABEL JADWAL PELAJARAN
         */
        Schema::create('lesson_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('teacher_id');
            $table->enum('school_level', ['SD', 'SMP', 'SMA']);
            $table->uuid('subject_id')->nullable();
            $table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');

            $table->unique(['teacher_id', 'day_of_week', 'start_time', 'end_time', 'subject_id'], 'uniq_teacher_schedule');
        });

        /**
         * TABEL ABSENSI SISWA
         */
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_id');
            $table->uuid('student_course_id');
            $table->uuid('teacher_id');
            $table->date('date');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Hadir');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('lesson_schedules')->onDelete('cascade');
            $table->foreign('student_course_id')->references('id')->on('student_courses')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });

        /**
         * TABEL ASSESSMENTS
         * - Penilaian mengacu ke teacher_student_courses (memastikan guru & siswa terhubung)
         * - subject_id menunjuk subject yang dinilai
         * - score: 1-100 (nullable jika ingin menyimpan catatan tanpa skor)
         */
        Schema::create('assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // referensi ke relasi teacher <-> student_course
            $table->uuid('teacher_student_course_id');
            // subject yang dinilai
            $table->uuid('subject_id')->nullable();

            // (opsional) simpan siapa yang melakukan penilaian (bisa guru atau admin)
            // menyimpan user id yang melakukan aksi (bukan sumber data relasi)
            $table->uuid('assessor_id');

            // nilai dan catatan
            $table->unsignedSmallInteger('score')->nullable(); // 0-65535, kita pakai 1-100
            $table->text('notes')->nullable();
            $table->date('assessment_date');

            $table->timestamps();

            // foreign keys
            $table->foreign('teacher_student_course_id')
                  ->references('id')->on('teacher_student_courses')
                  ->onDelete('cascade');

            $table->foreign('subject_id')
                  ->references('id')->on('subjects')
                  ->onDelete('set null');

            $table->foreign('assessor_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            // mencegah duplikat penilaian untuk kombinasi yang sama pada satu hari
            $table->unique(['teacher_student_course_id', 'subject_id', 'assessment_date'], 'uniq_assessment_per_day');
        });
    }

    public function down(): void
    {
        // urutan drop harus kebalikan dari pembuatan untuk menghindari FK error
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('lesson_schedules');
        Schema::dropIfExists('teacher_student_courses');
        Schema::dropIfExists('subjects');
    }
};
