<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel pengembalian aset — dicatat saat peminjam mengembalikan barang.
     */
    public function up(): void
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')
                ->unique()
                ->constrained('peminjamans')
                ->cascadeOnDelete();
            $table->foreignId('dikonfirmasi_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Pengelola yang mengkonfirmasi pengembalian');

            $table->date('tanggal_kembali_aktual')->comment('Tanggal aktual barang dikembalikan');
            $table->enum('kondisi_saat_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])
                ->default('baik');
            $table->text('catatan')->nullable();
            $table->string('bukti_pengembalian')->nullable()->comment('Foto atau dokumen bukti pengembalian (path)');
            $table->boolean('terlambat')->default(false);
            $table->integer('hari_terlambat')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
