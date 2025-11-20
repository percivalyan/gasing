<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->uuid('user_id')
                ->references('uuid')
                ->on('users')->onDelete('cascade');
        });

        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->uuid('gallery_id');
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->foreign('gallery_id')->references('id')->on('galleries')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeries');
        Schema::dropIfExists('galery_images');
    }
};
