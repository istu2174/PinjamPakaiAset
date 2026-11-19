<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel notifikasi in-app — untuk semua user (peminjam, pengelola, admin).
     * Digunakan juga untuk reminder otomatis H-7 s/d H-0.
     */
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('peminjaman_id')
                ->nullable()
                ->constrained('peminjamans')
                ->nullOnDelete()
                ->comment('Referensi peminjaman (opsional)');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', [
                'pengajuan',    // Notif ke pengelola: ada pengajuan baru
                'verifikasi',   // Notif ke peminjam: pengajuan diverifikasi
                'persetujuan',  // Notif ke peminjam: disetujui/ditolak admin
                'reminder',     // Reminder otomatis H-7 s/d H-0
                'pengembalian', // Notif terkait pengembalian barang
                'keluhan',      // Notif terkait keluhan
                'sistem',       // Notif umum dari sistem
            ])->default('sistem');
            $table->boolean('dibaca')->default(false);
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
