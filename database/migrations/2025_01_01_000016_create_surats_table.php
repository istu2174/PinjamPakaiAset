<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel surat pernyataan peminjaman — generate setelah peminjaman disetujui.
     * Barcode token digunakan untuk verifikasi identitas peminjam (auth-gated).
     */
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')
                ->unique()
                ->constrained('peminjamans')
                ->cascadeOnDelete();
            $table->string('nomor_surat')->unique()->comment('Nomor surat resmi, cth: 001/SIPA/DISPORA/2025');
            $table->date('tanggal_surat');
            $table->string('file_path')->nullable()->comment('Path file PDF surat yang sudah digenerate');
            $table->string('barcode_token', 64)->unique()
                ->comment('Token unik untuk verifikasi barcode — hanya valid jika user sudah login');
            $table->timestamp('barcode_diakses_at')->nullable()
                ->comment('Waktu terakhir barcode dipindai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
