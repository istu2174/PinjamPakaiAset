<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel input kriteria Decision Tree — pengelola mengisi data ini
     * untuk menentukan kelayakan sebuah aset.
     */
    public function up(): void
    {
        Schema::create('kondisi_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')
                ->constrained('asets')
                ->cascadeOnDelete();
            $table->foreignId('pengelola_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('User pengelola yang melakukan penilaian');

            // --- Kriteria Decision Tree ---
            $table->unsignedTinyInteger('kondisi_fisik')
                ->comment('Skala 1-5: 1=rusak berat, 5=sangat baik');
            $table->integer('usia_aset')
                ->comment('Usia aset dalam tahun');
            $table->boolean('riwayat_perawatan_rutin')
                ->default(false)
                ->comment('Apakah aset punya riwayat perawatan rutin');
            $table->unsignedTinyInteger('frekuensi_penggunaan')
                ->comment('Skala 1-5: 1=sangat jarang, 5=sangat sering');
            $table->boolean('masih_bisa_diperbaiki')
                ->default(true)
                ->comment('Apakah aset masih bisa diperbaiki jika rusak');
            // ----------------------------------

            $table->enum('hasil_decision_tree', ['layak_pakai', 'perlu_perawatan', 'layak_lelang'])
                ->comment('Output algoritma Decision Tree');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_asets');
    }
};
