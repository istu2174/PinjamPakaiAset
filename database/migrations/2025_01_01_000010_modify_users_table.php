<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambah kolom khusus SIPANDA ke tabel users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['peminjam', 'pengelola', 'admin'])->default('peminjam')->after('email');
            $table->string('nik', 16)->nullable()->unique()->after('role');
            $table->string('no_hp', 15)->nullable()->after('nik');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->string('foto_ktp')->nullable()->after('alamat');
            $table->enum('jenis_peminjam', ['pemerintah', 'organisasi', 'umum'])->default('umum')->after('foto_ktp');
            $table->unsignedTinyInteger('priority_score')->default(1)->after('jenis_peminjam')
                ->comment('1=umum, 2=organisasi, 3=pemerintah — digunakan Priority Scheduling');
            $table->boolean('is_active')->default(true)->after('priority_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'nik', 'no_hp', 'alamat', 'foto_ktp',
                'jenis_peminjam', 'priority_score', 'is_active',
            ]);
        });
    }
};
