<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel inti — pengajuan peminjaman aset oleh peminjam.
     * Priority score diisi oleh sistem berdasarkan Priority Scheduling.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjam_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignId('aset_id')
                ->constrained('asets')
                ->restrictOnDelete();

            // --- Data Peminjaman ---
            $table->string('nomor_pengajuan')->unique()->comment('Nomor unik pengajuan, cth: PJM-2025-001');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->text('keperluan')->comment('Tujuan / keperluan peminjaman');
            $table->string('dokumen_pendukung')->nullable()->comment('File surat atau dokumen pendukung (path)');

            // --- Priority Scheduling ---
            $table->unsignedTinyInteger('priority_score')
                ->default(1)
                ->comment('Skor prioritas dari Priority Scheduling: 1=umum, 2=organisasi, 3=pemerintah');
            $table->boolean('konflik_jadwal')
                ->default(false)
                ->comment('Apakah ada konflik jadwal dengan peminjaman lain');

            // --- Status & Keputusan ---
            $table->enum('status', [
                'pending',       // Baru diajukan
                'diverifikasi',  // Sudah diverifikasi pengelola
                'disetujui',     // Disetujui admin
                'ditolak',       // Ditolak (oleh pengelola atau admin)
                'dipinjam',      // Barang sudah diambil
                'selesai',       // Barang sudah dikembalikan
                'dibatalkan',    // Dibatalkan peminjam
            ])->default('pending');

            $table->text('catatan_pengelola')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('alasan_penolakan')->nullable();

            // --- Tracking ---
            $table->timestamp('diverifikasi_at')->nullable();
            $table->timestamp('disetujui_at')->nullable();
            $table->timestamp('ditolak_at')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
