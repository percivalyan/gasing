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
         * 1. Lokasi penyimpanan aset/inventaris
         * --------------------------------------
         * Contoh: Gudang Utama, Ruang Kelas, Kantor, dll
         */
        Schema::create('inventory_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        /**
         * 2. Data Barang / Inventaris
         * --------------------------------------
         */
        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Nomor / kode inventaris
            $table->string('inventory_number', 100)->unique();

            // Nama barang
            $table->string('item_name', 150);

            // Deskripsi barang
            $table->text('description')->nullable();

            // Lokasi penyimpanan
            $table->uuid('location_id')->nullable();
            $table->foreign('location_id')
                ->references('id')
                ->on('inventory_locations')
                ->onDelete('set null');

            // Status aset
            $table->enum('status', ['active', 'damaged', 'loaned'])->default('active')->index();

            // Tanggal perolehan
            $table->date('acquired_date')->nullable();

            // Nilai / harga barang (jika perlu untuk laporan keuangan)
            $table->decimal('value', 15, 2)->nullable();

            // Penanggung jawab
            $table->string('responsible_person', 150)->nullable();

            // Keterangan tambahan
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * 3. Riwayat Peminjaman Barang
         * --------------------------------------
         * Catatan siapa yang meminjam, kapan, dan status pengembalian.
         */
        Schema::create('inventory_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Barang yang dipinjam
            $table->uuid('inventory_id');
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->onDelete('cascade');

            // Peminjam
            $table->string('borrower_name', 150);
            $table->string('borrower_contact', 50)->nullable();

            // Tanggal pinjam & kembali
            $table->date('loan_date');
            $table->date('return_date')->nullable();

            // Status pinjaman: aktif / dikembalikan
            $table->enum('loan_status', ['borrowed', 'returned'])->default('borrowed')->index();

            // Keterangan tambahan
            $table->text('notes')->nullable();

            $table->timestamps();
        });

        /**
         * 4. Riwayat Perawatan Barang
         * --------------------------------------
         * Menyimpan data perbaikan / maintenance.
         */
        Schema::create('inventory_maintenances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Barang yang dirawat
            $table->uuid('inventory_id');
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->onDelete('cascade');

            // Tanggal perawatan
            $table->date('maintenance_date');

            // Jenis / deskripsi perawatan
            $table->string('maintenance_type', 150)->nullable();
            $table->text('description')->nullable();

            // Biaya perawatan (opsional)
            $table->decimal('cost', 15, 2)->nullable();

            // Pihak yang melakukan perawatan
            $table->string('performed_by', 150)->nullable();

            // Keterangan tambahan
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_maintenances');
        Schema::dropIfExists('inventory_loans');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('inventory_locations');
    }
};
