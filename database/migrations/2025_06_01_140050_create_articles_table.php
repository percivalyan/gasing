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
        // Tabel kategori artikel
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        // Tabel artikel
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('title', 40);
            $table->string('summary', 50);
            $table->longText('content', 12000);
            $table->string('image_path')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();

            // Relasi ke categories
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            // Relasi ke users (pakai uuid)
            $table->uuid('user_id')
                ->references('uuid')
                ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('categories');
    }
};
