<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Relasi sederhana hanya ke users (teacher)
            $table->uuid('teacher_id')->index();
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

            // tanggal & waktu
            $table->date('attendance_date')->index();
            $table->timestamp('checkin_at')->nullable();
            $table->timestamp('checkout_at')->nullable();

            // lokasi opsional
            $table->decimal('checkin_lat', 10, 7)->nullable();
            $table->decimal('checkin_lng', 10, 7)->nullable();
            $table->integer('checkin_accuracy')->nullable();

            // status / izin / note
            $table->enum('status', ['present', 'late', 'permission', 'absent'])->default('present');
            $table->string('permission_type')->nullable(); // contoh: 'sick','official','personal'
            $table->text('note')->nullable();

            // metadata
            $table->string('checkin_ip', 45)->nullable();
            $table->string('photo', 255)->nullable(); // path foto bukti
            $table->enum('method', ['auto', 'manual'])->default('auto');

            $table->timestamps();

            // unik per guru per hari (mencegah duplikat)
            $table->unique(['teacher_id', 'attendance_date'], 'unique_teacher_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_courses');
    }
};
