<?php

namespace Database\Seeders;

use App\Models\KategoriAset;
use Illuminate\Database\Seeder;

class KategoriAsetSeeder extends Seeder
{
    /**
     * Seed kategori aset yang umum digunakan di Dinas Kepemudaan dan Olahraga.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Peralatan Komputer & Elektronik', 'deskripsi' => 'Laptop portabel, komputer All-in-One, tablet, printer, dan perangkat IT pendukung'],
            ['nama' => 'Kamera & Multimedia',            'deskripsi' => 'Kamera mirrorless studio, wireless microphone, portable power station, dan audio PA system'],
            ['nama' => 'Perlengkapan Acara & Event',      'deskripsi' => 'Kursi acara Chitose/Futura, meja lipat/rapat, dan tenda kegiatan Dispora'],
            ['nama' => 'Peralatan Olahraga',              'deskripsi' => 'Peralatan dan perlengkapan sarana olahraga (tenis meja, net voli, matras senam, bola)'],
            ['nama' => 'Kendaraan Dinas (Internal)',      'deskripsi' => 'Kendaraan dinas operasional internal Dispora (Khusus kedinasan internal - tidak dipinjamkan)'],
            ['nama' => 'Inventaris Sarana Kantor (Internal)', 'deskripsi' => 'Fasilitas dan mebel permanen kantor Dispora (Lemari arsip, filing cabinet, partisi, AC dinding)'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriAset::create($kategori);
        }

        $this->command->info('✓ 6 kategori aset berhasil dibuat.');
    }
}
