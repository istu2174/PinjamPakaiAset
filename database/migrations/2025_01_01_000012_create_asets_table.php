<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel aset — data barang milik Dinas Kepemudaan dan Olahraga.
     */
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')
                ->constrained('kategori_asets')
                ->restrictOnDelete();
            $table->string('kode_aset')->unique()->comment('Kode unik aset, cth: ASET-001');
            $table->string('nama_aset');
            $table->string('merk')->nullable()->comment('Merk / brand aset');
            $table->text('deskripsi')->nullable();
            $table->integer('tahun_pengadaan')->nullable();
            $table->string('lokasi')->nullable()->comment('Lokasi fisik penyimpanan aset');
            $table->integer('jumlah')->default(1)->comment('Jumlah unit aset tersedia');
            $table->enum('kondisi', ['layak_pakai', 'perlu_perawatan', 'layak_lelang'])
                ->default('layak_pakai')
                ->comment('Hasil klasifikasi Decision Tree');
            $table->enum('status', ['tersedia', 'dipinjam', 'maintenance', 'tidak_aktif'])
                ->default('tersedia');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
