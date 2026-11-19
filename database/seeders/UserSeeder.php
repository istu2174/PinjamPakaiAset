<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\PrioritySchedulingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Buat akun default untuk setiap role.
     * Gunakan akun ini untuk development & testing.
     */
    public function run(): void
    {
        // --- Admin ---
        User::create([
            'name'           => 'Administrator SIPANDA',
            'email'          => 'admin@sipanda.test',
            'password'       => Hash::make('password'),
            'role'           => 'admin',
            'nik'            => '1671000000000001',
            'no_hp'          => '081234567890',
            'alamat'         => 'Dinas Kepemudaan dan Olahraga Kota Palembang',
            'jenis_peminjam' => 'pemerintah',
            'priority_score' => 3,
            'is_active'      => true,
        ]);

        // --- Pengelola ---
        User::create([
            'name'           => 'Pengelola Aset',
            'email'          => 'pengelola@sipanda.test',
            'password'       => Hash::make('password'),
            'role'           => 'pengelola',
            'nik'            => '1671000000000002',
            'no_hp'          => '081234567891',
            'alamat'         => 'Dinas Kepemudaan dan Olahraga Kota Palembang',
            'jenis_peminjam' => 'pemerintah',
            'priority_score' => 3,
            'is_active'      => true,
        ]);

        // --- Peminjam (Pemerintah — Prioritas Tinggi) ---
        User::create([
            'name'           => 'Budi Santoso',
            'email'          => 'peminjam.pemerintah@sipanda.test',
            'password'       => Hash::make('password'),
            'role'           => 'peminjam',
            'nik'            => '1671000000000003',
            'no_hp'          => '082345678901',
            'alamat'         => 'Jl. Merdeka No. 1, Palembang',
            'jenis_peminjam' => 'pemerintah',
            'priority_score' => PrioritySchedulingService::getSkor('pemerintah'),
            'is_active'      => true,
        ]);

        // --- Peminjam (Organisasi — Prioritas Sedang) ---
        User::create([
            'name'           => 'Siti Rahayu',
            'email'          => 'peminjam.organisasi@sipanda.test',
            'password'       => Hash::make('password'),
            'role'           => 'peminjam',
            'nik'            => '1671000000000004',
            'no_hp'          => '083456789012',
            'alamat'         => 'Jl. Sudirman No. 10, Palembang',
            'jenis_peminjam' => 'organisasi',
            'priority_score' => PrioritySchedulingService::getSkor('organisasi'),
            'is_active'      => true,
        ]);

        // --- Peminjam (Umum — Prioritas Rendah) ---
        User::create([
            'name'           => 'Ahmad Fauzi',
            'email'          => 'peminjam.umum@sipanda.test',
            'password'       => Hash::make('password'),
            'role'           => 'peminjam',
            'nik'            => '1671000000000005',
            'no_hp'          => '084567890123',
            'alamat'         => 'Jl. Ahmad Yani No. 5, Palembang',
            'jenis_peminjam' => 'umum',
            'priority_score' => PrioritySchedulingService::getSkor('umum'),
            'is_active'      => true,
        ]);

        // --- Peminjam Baru (Menunggu Verifikasi Admin) ---
        User::create([
            'name'           => 'Rian Pratama (Menunggu Verifikasi)',
            'email'          => 'peminjam.baru@sipanda.test',
            'password'       => Hash::make('password'),
            'role'           => 'peminjam',
            'nik'            => '1671000000000006',
            'no_hp'          => '085678901234',
            'alamat'         => 'Jl. Kolonel Atmo No. 12, Palembang',
            'jenis_peminjam' => 'umum',
            'priority_score' => PrioritySchedulingService::getSkor('umum'),
            'is_active'      => false, // Belum diverifikasi
        ]);

        // --- Peminjam Khusus (Dikecualikan dari Rekap Bulanan, menunggu verifikasi admin) ---
        User::create([
            'name'                 => 'Akun Internal Dispora',
            'email'                => 'peminjam.internal@sipanda.test',
            'password'             => Hash::make('password'),
            'role'                 => 'peminjam',
            'nik'                  => '1671000000000007',
            'no_hp'                => '086789012345',
            'alamat'               => 'Dinas Kepemudaan dan Olahraga Kota Palembang',
            'jenis_peminjam'       => 'pemerintah',
            'priority_score'       => PrioritySchedulingService::getSkor('pemerintah'),
            'is_active'            => false, // Wajib diverifikasi admin
            'dikecualikan_rekap'   => true,  // Aktivitas tidak dihitung dalam rekap bulanan
            'catatan_pengecualian' => 'Akun internal/uji coba — tidak dihitung dalam rekap bulanan.',
        ]);

        $this->command->info('✓ 5 akun default berhasil dibuat:');
        $this->command->table(
            ['Email', 'Role', 'Password'],
            [
                ['admin@sipanda.test',                  'admin',     'password'],
                ['pengelola@sipanda.test',              'pengelola', 'password'],
                ['peminjam.pemerintah@sipanda.test',   'peminjam',  'password'],
                ['peminjam.organisasi@sipanda.test',   'peminjam',  'password'],
                ['peminjam.umum@sipanda.test',         'peminjam',  'password'],
                ['peminjam.internal@sipanda.test',     'peminjam (tidak direkap)', 'password'],
            ]
        );
    }
}
