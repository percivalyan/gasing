<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ====== TABEL ABOUT ======
        Schema::create('abouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('vision')->nullable();      // Visi Yayasan
            $table->longText('mission')->nullable(); // Misi Yayasan
            $table->longText('history')->nullable(); // Sejarah Yayasan
            $table->timestamps();
        });

        // ====== TABEL ORGANIZATION STRUCTURE ======
        // NAMA JABATAN / POSISI DI STRUKTUR ORGANISASI
        Schema::create('organization_structures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('position');  
            $table->integer('order')->default(0);    // urutan tampilan
            $table->timestamps();
        });

        // ====== TABEL ORGANIZATION MEMBERS ======
        // ANGGOTA YANG MENGISI POSISI DI STRUKTUR ORGANISASI
        Schema::create('organization_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('structure_id');            // relasi ke posisi
            $table->string('name');                  // nama anggota
            $table->integer('order')->default(0);    // urutan tampil
            $table->timestamps();

            $table->foreign('structure_id')->references('id')->on('organization_structures')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_members');
        Schema::dropIfExists('organization_structures');
        Schema::dropIfExists('abouts');
    }
};
