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
         * 1. Tabel Kategori Keuangan
         * -------------------------------------
         * Contoh data: Donasi, Operasional, Kegiatan, dll.
         */
        Schema::create('financial_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false); // kategori bawaan sistem
            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * 2. Tabel Transaksi Keuangan
         * -------------------------------------
         * Menyimpan pemasukan & pengeluaran.
         */
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // relasi kategori transaksi
            $table->uuid('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('financial_categories')
                ->onDelete('set null');

            $table->enum('transaction_type', ['income', 'expense'])->index();
            $table->string('title', 200);
            $table->text('notes')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date')->index();
            $table->string('source', 191)->nullable();
            $table->string('payment_method', 50)->nullable();

            // pengguna yang mencatat
            $table->uuid('created_by')->nullable();

            // status approval
            $table->boolean('is_approved')->default(false)->index();
            $table->timestamp('approved_at')->nullable();
            $table->uuid('approved_by')->nullable();

            $table->string('reference_no', 120)->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });

        /**
         * 3. Tabel Lampiran Transaksi (Bukti Upload)
         * -------------------------------------
         * Simpan file bukti transaksi (nota, kwitansi, foto, dll)
         */
        Schema::create('transaction_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // relasi ke transaksi
            $table->uuid('transaction_id');
            $table->foreign('transaction_id')
                ->references('id')
                ->on('financial_transactions')
                ->onDelete('cascade');

            $table->string('file_name', 255);
            $table->string('file_path', 512); // path di storage
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('label', 150)->nullable();

            $table->uuid('uploaded_by')->nullable();
            $table->timestamps();
        });

        /**
         * Index tambahan untuk laporan & performa
         */
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->index(['transaction_date', 'transaction_type']);
        });

        Schema::table('transaction_attachments', function (Blueprint $table) {
            $table->index(['transaction_id']);
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_attachments');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('financial_categories');
    }
};
