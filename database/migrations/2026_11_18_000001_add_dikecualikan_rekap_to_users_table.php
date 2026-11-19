<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Penanda akun peminjam yang aktivitas peminjaman & pengembaliannya
     * TIDAK dihitung dalam rekap/laporan bulanan (ditandai oleh Administrator).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('dikecualikan_rekap')->default(false)->after('is_active');
            $table->text('catatan_pengecualian')->nullable()->after('dikecualikan_rekap');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dikecualikan_rekap', 'catatan_pengecualian']);
        });
    }
};
