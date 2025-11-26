<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // USERS
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->rememberToken();
            $table->timestamps();

            // Umum
            $table->string('nik', 30)->nullable()->unique();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();

            // Private lesson teachers / instructors
            $table->string('nip', 30)->nullable()->unique();
            $table->string('expertise_field', 30)->nullable();
            $table->string('last_education', 30)->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->text('address')->nullable();
        });

        // PASSWORD RESET
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // SESSIONS
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('action')->nullable();
            $table->string('description')->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload')->nullable();
            $table->integer('last_activity')->index();
        });

        // STUDENT COURSES
        Schema::create('student_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name');
            $table->string('nik', 30)->nullable()->unique();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F']);
            $table->text('address')->nullable();
            $table->string('origin_district')->nullable();
            $table->enum('school_level', ['SD', 'SMP', 'SMA'])->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('dream')->nullable();
            $table->enum('fee_note', ['yellow', 'red', 'green'])->default('yellow');
            $table->string('note')->nullable();
            $table->string('school_origin', 100)->nullable();
            $table->timestamps();
        });

        // STUDENT EVENTS
        Schema::create('student_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name');
            $table->string('nik', 30)->nullable()->unique();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->text('address')->nullable();
            $table->string('origin_district')->nullable();
            $table->enum('school_level', ['SD', 'SMP', 'SMA'])->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('dream')->nullable();
            $table->string('school_origin', 100)->nullable();
            $table->string('photo')->nullable();
            $table->string('letter_of_assignment')->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->timestamps();
        });

        // TEACHER EVENTS
        Schema::create('teacher_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name', 50);
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('nip', 30)->nullable()->unique();
            $table->string('expertise_field', 50)->nullable();
            $table->string('last_education', 50)->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('school_origin', 100)->nullable();
            $table->string('photo')->nullable();
            $table->string('letter_of_assignment')->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->timestamps();
        });

        /**
         * ASSIGN STUDENT EVENT KE TEACHER EVENT
         */
        Schema::create('teacher_student_events', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // guru yang membimbing event
            $table->uuid('teacher_event_id');

            // murid yang mengikuti event
            $table->uuid('student_event_id');

            // tanggal assign
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // status assign
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            $table->timestamps();

            // RELASI FOREIGN KEY
            $table->foreign('teacher_event_id')
                ->references('id')->on('teacher_events')
                ->onDelete('cascade');

            $table->foreign('student_event_id')
                ->references('id')->on('student_events')
                ->onDelete('cascade');

            // mencegah duplikasi assign teacher–student event
            $table->unique(['teacher_event_id', 'student_event_id'], 'uniq_teacher_student_event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_events');
        Schema::dropIfExists('student_events');
        Schema::dropIfExists('student_courses');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('teacher_student_events');
    }
};
