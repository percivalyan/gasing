<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('private');
            $table->timestamps();

            $table->uuid('user_id')
                ->references('uuid')
                ->on('users')->onDelete('cascade');
        });

        Schema::create('letter_types', function (Blueprint $table) {
            $table->string('id')->primary(); // misal: 'SK', 'SP', 'SU'
            $table->string('subject');
            $table->string('code')->unique(); // kode resmi, misal: "01/SK/IMAPA"
            $table->timestamps();
        });

        Schema::create('reference_numbers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('letter_type_id')->nullable();
            $table->foreign('letter_type_id')->references('id')->on('letter_types')->nullOnDelete();

            $table->unsignedInteger('serial_number'); // bukan unique global
            $table->string('institution')->default('IMAPA');
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->string('ref')->unique();
            $table->uuid('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('reference_number_trackers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('letter_type_id')->unique();
            $table->unsignedInteger('current_number')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_numbers');
        Schema::dropIfExists('letter_types');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('reference_number_trackers');
    }
};
