<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel keluhan — peminjam dapat mengajukan keluhan terkait aset atau layanan.
     */
    public function up(): void
    {
        Schema::create('keluhanss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('Peminjam yang mengajukan keluhan');
            $table->foreignId('peminjaman_id')
                ->nullable()
                ->constrained('peminjamans')
                ->nullOnDelete()
                ->comment('Keluhan terkait peminjaman tertentu (opsional)');
            $table->foreignId('ditangani_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Pengelola yang menangani keluhan');

            $table->string('judul');
            $table->text('isi');
            $table->string('lampiran')->nullable()->comment('File lampiran keluhan (path)');
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->text('respon_pengelola')->nullable();
            $table->timestamp('direspon_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhanss');
    }
};
