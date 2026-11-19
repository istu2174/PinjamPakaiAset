<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Database\Seeder;

class AsetSeeder extends Seeder
{
    /**
     * Seed data aset riil milik Dinas Kepemudaan dan Olahraga Kota Palembang
     * berdasarkan dokumen Barang Milik Daerah (KIB B Dispora Palembang).
     */
    public function run(): void
    {
        $kategoris = KategoriAset::pluck('id', 'nama');

        $asets = [
            // =================================================================
            // 1. PERALATAN KOMPUTER & ELEKTRONIK (Bisa Dipinjam)
            // =================================================================
            [
                'nama_aset'       => 'Laptop Acer Aspire A314 Core i5',
                'merk'            => 'Acer Aspire A314-52G',
                'kode_aset'       => '1201167119-2023-NB01',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Ruang IT & Perlengkapan Dispora',
                'jumlah'          => 8,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Laptop operasional Intel Core i5 RAM 8GB SSD 512GB, tas charger lengkap. Pengadaan APBD 2023.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],
            [
                'nama_aset'       => 'Laptop Acer Predator High Performance',
                'merk'            => 'Acer Predator Helios',
                'kode_aset'       => '1201167119-2022-NB02',
                'tahun_pengadaan' => 2022,
                'lokasi'          => 'Bidang Peningkatan Prestasi Olahraga',
                'jumlah'          => 2,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Laptop spesifikasi tinggi untuk pengolahan video/event/scoring pertandingan. Pengadaan APBD 2022.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],
            [
                'nama_aset'       => 'Laptop Lenovo IdeaPad Slim',
                'merk'            => 'Lenovo IdeaPad 110 / V110',
                'kode_aset'       => '1201167119-2021-NB03',
                'tahun_pengadaan' => 2021,
                'lokasi'          => 'Gudang Perlengkapan IT',
                'jumlah'          => 3,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Laptop portabel untuk kegiatan administrasi lapangan dan kepanitiaan Dispora.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],
            [
                'nama_aset'       => 'Komputer Asus All-in-One (PC AIO)',
                'merk'            => 'Asus PC AIO M241',
                'kode_aset'       => '1201167119-2023-AIO01',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Ruang Rapat & Pelatihan Dispora',
                'jumlah'          => 5,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Komputer desktop All-in-One 24 inch lengkap keyboard dan mouse wireless untuk acara/workshop.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],
            [
                'nama_aset'       => 'Tablet Samsung Galaxy Tab',
                'merk'            => 'Samsung Galaxy Tab A8 LTE',
                'kode_aset'       => '1201167119-2024-TAB01',
                'tahun_pengadaan' => 2024,
                'lokasi'          => 'Ruang Perencanaan & Pelaporan',
                'jumlah'          => 7,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Belanja Modal Komputer TAB 10.5 inch, cocok untuk registrasi peserta acara/survei lapangan.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],
            [
                'nama_aset'       => 'Printer Multifungsi Epson EcoTank L3250',
                'merk'            => 'Epson EcoTank L3250 Wi-Fi',
                'kode_aset'       => '1201167119-2022-PRN01',
                'tahun_pengadaan' => 2022,
                'lokasi'          => 'Ruang Umum & Kepegawaian',
                'jumlah'          => 11,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Printer tangki tinta original Print-Scan-Copy dengan koneksi Wi-Fi portabel.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],
            [
                'nama_aset'       => 'Proyektor Epson + Layar Tripod 70 Inch',
                'merk'            => 'Epson EB-X500 3600 Lumens',
                'kode_aset'       => '1201167119-2021-PRJ01',
                'tahun_pengadaan' => 2021,
                'lokasi'          => 'Ruang Rapat Utama Dispora',
                'jumlah'          => 4,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Proyektor terang 3600 ANSI lumens portabel lengkap tas, kabel HDMI 15m, dan tripod screen.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],
            [
                'nama_aset'       => 'UPS ICA 1200VA Portable Backup Power',
                'merk'            => 'ICA CP1200',
                'kode_aset'       => '1201167119-2024-UPS01',
                'tahun_pengadaan' => 2024,
                'lokasi'          => 'Gudang Logistik IT',
                'jumlah'          => 6,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Unit catu daya cadangan portable untuk mencegah peralatan mati saat event outdoor/rapat.',
                'kategori'        => 'Peralatan Komputer & Elektronik',
            ],

            // =================================================================
            // 2. KAMERA & MULTIMEDIA (Bisa Dipinjam)
            // =================================================================
            [
                'nama_aset'       => 'Kamera Mirrorless Sony Alpha 7 III',
                'merk'            => 'Sony Alpha 7 III (Body + Lens 28-70mm)',
                'kode_aset'       => '1201167119-2023-CAM01',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Studio Dokumentasi & Humas Dispora',
                'jumlah'          => 2,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Kamera Full-Frame profesional untuk dokumentasi kegiatan olahraga dan pemuda, strap, 2 baterai + charger.',
                'kategori'        => 'Kamera & Multimedia',
            ],
            [
                'nama_aset'       => 'Wireless Microphone Dual Channel Saramonic',
                'merk'            => 'Saramonic Blink 500 B2',
                'kode_aset'       => '1201167119-2023-MIC01',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Studio Dokumentasi & Humas Dispora',
                'jumlah'          => 3,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Mic clip-on wireless 2 pemancar (TX) + 1 penerima (RX) untuk rekaman audio wawancara/liputan.',
                'kategori'        => 'Kamera & Multimedia',
            ],
            [
                'nama_aset'       => 'Portable Power Station Generator Baterai',
                'merk'            => 'Bluetti EB70 1000W',
                'kode_aset'       => '1201167119-2023-PWR01',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Ruang Sarana Olahraga',
                'jumlah'          => 2,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Pembangkit listrik portable tanpa bising/asap untuk suplai sound dan penerangan di lapangan olahraga.',
                'kategori'        => 'Kamera & Multimedia',
            ],
            [
                'nama_aset'       => 'Portable Wireless PA Sound System Dispora',
                'merk'            => 'Baretone BT-3H1515BWR 15 Inch',
                'kode_aset'       => '1201167119-2022-SND01',
                'tahun_pengadaan' => 2022,
                'lokasi'          => 'Gudang Sound & Audio',
                'jumlah'          => 4,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Speaker portable 15 inch dengan roda troli, include 2 mic wireless genggam, Bluetooth, USB, baterai tahan 6 jam.',
                'kategori'        => 'Kamera & Multimedia',
            ],

            // =================================================================
            // 3. PERLENGKAPAN ACARA & EVENT (Bisa Dipinjam)
            // =================================================================
            [
                'nama_aset'       => 'Kursi Acara Chitose Caesar-N',
                'merk'            => 'Chitose Caesar-N Stainless',
                'kode_aset'       => '1201167119-2025-KRS01',
                'tahun_pengadaan' => 2025,
                'lokasi'          => 'Gudang Logistik & Acara Dispora',
                'jumlah'          => 50,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Kursi besi/metal lapis busa empuk Chitose standar acara kenegaraan dan seremonial Dispora. Pengadaan APBD 2025.',
                'kategori'        => 'Perlengkapan Acara & Event',
            ],
            [
                'nama_aset'       => 'Kursi Acara Levante LV 28',
                'merk'            => 'Levante LV 28',
                'kode_aset'       => '1201167119-2023-KRS02',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Gudang Logistik & Acara Dispora',
                'jumlah'          => 30,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Kursi susun rangka chrome tebal untuk tamu undangan dan peserta kegiatan kepemudaan.',
                'kategori'        => 'Perlengkapan Acara & Event',
            ],
            [
                'nama_aset'       => 'Kursi Lipat Futura',
                'merk'            => 'Futura FTR-405',
                'kode_aset'       => '1201167119-2020-KRS03',
                'tahun_pengadaan' => 2020,
                'lokasi'          => 'Gudang Logistik',
                'jumlah'          => 20,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Kursi lipat praktis mudah diangkut untuk acara luar ruangan atau tribun tambahan.',
                'kategori'        => 'Perlengkapan Acara & Event',
            ],
            [
                'nama_aset'       => 'Meja Lipat Rapat & Acara Chitose',
                'merk'            => 'Chitose Foldable Table 180cm',
                'kode_aset'       => '1201167119-2020-MJA01',
                'tahun_pengadaan' => 2020,
                'lokasi'          => 'Gudang Logistik',
                'jumlah'          => 30,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Meja lipat serbaguna panjang 180cm kapasitas 3-4 orang, cocok untuk meja registrasi dan rapat panitia.',
                'kategori'        => 'Perlengkapan Acara & Event',
            ],
            [
                'nama_aset'       => 'Tenda Pesta Besar Dispora 5x10 Meter',
                'merk'            => 'Alu-Tent Heavy Duty',
                'kode_aset'       => '1201167119-2021-TND01',
                'tahun_pengadaan' => 2021,
                'lokasi'          => 'Gudang Lapangan Dispora',
                'jumlah'          => 2,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Tenda kerangka besi pipa kokoh ukuran 5x10 meter lengkap terpal plafon putih untuk posko/event outdoor.',
                'kategori'        => 'Perlengkapan Acara & Event',
            ],
            [
                'nama_aset'       => 'Tenda Dome & Promosi Dispora 3x3 Meter',
                'merk'            => 'Consina Event Tent',
                'kode_aset'       => '1201167119-2022-TND02',
                'tahun_pengadaan' => 2022,
                'lokasi'          => 'Gudang Lapangan Dispora',
                'jumlah'          => 5,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Tenda lipat gazebo cepat pasang ukuran 3x3 meter untuk booth informasi, medis, atau pendaftaran event.',
                'kategori'        => 'Perlengkapan Acara & Event',
            ],

            // =================================================================
            // 4. PERALATAN OLAHRAGA (Bisa Dipinjam)
            // =================================================================
            [
                'nama_aset'       => 'Meja Tenis Meja Standar Turnamen',
                'merk'            => 'DHS Rainbow Standar ITTF',
                'kode_aset'       => '1201167119-2021-OLH01',
                'tahun_pengadaan' => 2021,
                'lokasi'          => 'Gedung Olahraga Dispora Palembang',
                'jumlah'          => 4,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Meja pingpong standar nasional/internasional, include tiang, jaring net, dan roda pengunci.',
                'kategori'        => 'Peralatan Olahraga',
            ],
            [
                'nama_aset'       => 'Net Voli Portabel SNI + Tiang',
                'merk'            => 'Mikasa SNI Gold Standard',
                'kode_aset'       => '1201167119-2022-OLH02',
                'tahun_pengadaan' => 2022,
                'lokasi'          => 'Gudang Olahraga Dispora',
                'jumlah'          => 6,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Jaring net bola voli standar perlombaan kawat baja seling dan sepasang tiang portabel pemberat.',
                'kategori'        => 'Peralatan Olahraga',
            ],
            [
                'nama_aset'       => 'Matras Senam & Bela Diri Tebal 5cm',
                'merk'            => 'Yonex / Taekwondo Mat SNI',
                'kode_aset'       => '1201167119-2020-OLH03',
                'tahun_pengadaan' => 2020,
                'lokasi'          => 'Gedung Olahraga Dispora Palembang',
                'jumlah'          => 10,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Matras puzzle interlocking busa EVA padat tebal 5cm ukuran 1x1m, aman untuk pencak silat, karate, senam.',
                'kategori'        => 'Peralatan Olahraga',
            ],
            [
                'nama_aset'       => 'Bola Basket Standar Kompetisi',
                'merk'            => 'Spalding TF-1000 Size 7',
                'kode_aset'       => '1201167119-2023-OLH04',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Gudang Olahraga Dispora',
                'jumlah'          => 12,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Bola basket kulit komposit resmi turnamen Perbasi ukuran 7.',
                'kategori'        => 'Peralatan Olahraga',
            ],
            [
                'nama_aset'       => 'Bola Voli Mikasa & Bola Futsal Molten',
                'merk'            => 'Mikasa V200W / Molten Vantaggio',
                'kode_aset'       => '1201167119-2023-OLH05',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Gudang Olahraga Dispora',
                'jumlah'          => 20,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => true,
                'deskripsi'       => 'Set bola voli resmi mikasa (10 unit) dan bola futsal standar FIFA (10 unit).',
                'kategori'        => 'Peralatan Olahraga',
            ],

            // =================================================================
            // 5. KENDARAAN DINAS OPERASIONAL (Internal - Tidak Bisa Dipinjam)
            // =================================================================
            [
                'nama_aset'       => 'Mobil Mini Bus Honda CR-V (BG 1310 RZ)',
                'merk'            => 'Honda CR-V 2.0 AT',
                'kode_aset'       => '120116711900000000000020151-CRV',
                'tahun_pengadaan' => 2015,
                'lokasi'          => 'Garasi Kantor Dispora Palembang',
                'jumlah'          => 1,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // KENDARAAN DINAS GABISA DIPINJEM
                'deskripsi'       => 'Mobil Dinas Operasional Pimpinan Dispora Kota Palembang. No. Polisi: BG 1310 RZ. Pengadaan APBD 2015.',
                'kategori'        => 'Kendaraan Dinas (Internal)',
            ],
            [
                'nama_aset'       => 'Mobil Mini Bus Mitsubishi Xpander Cross (BG 1650 IZ)',
                'merk'            => 'Mitsubishi Xpander Cross 1.5L 4X2 AT',
                'kode_aset'       => '120116711900000000000020221-XPD',
                'tahun_pengadaan' => 2022,
                'lokasi'          => 'Garasi Kantor Dispora Palembang',
                'jumlah'          => 1,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // KENDARAAN DINAS GABISA DIPINJEM
                'deskripsi'       => 'Mobil Dinas Operasional Bidang Dispora Kota Palembang. No. Polisi: BG 1650 IZ. Pengadaan APBD 2022.',
                'kategori'        => 'Kendaraan Dinas (Internal)',
            ],
            [
                'nama_aset'       => 'Mobil Mini Bus Daihatsu Terios TX MT (BG 1473 IZ & BG 1474 IZ)',
                'merk'            => 'Daihatsu Terios F700RG-TX MT',
                'kode_aset'       => '120116711900000000000020121-TRS',
                'tahun_pengadaan' => 2012,
                'lokasi'          => 'Garasi Kantor Dispora Palembang',
                'jumlah'          => 2,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // KENDARAAN DINAS GABISA DIPINJEM
                'deskripsi'       => 'Mobil Mini Bus 7 Penumpang operasional dinas Dispora. No. Polisi BG 1473 IZ & BG 1474 IZ.',
                'kategori'        => 'Kendaraan Dinas (Internal)',
            ],
            [
                'nama_aset'       => 'Mobil Pick Up Suzuki Carry AEV415W (BG 8934 MZ)',
                'merk'            => 'Suzuki AEV415W CX TYPE 2 (4X2) MT',
                'kode_aset'       => '120116711900000000000020221-PKP',
                'tahun_pengadaan' => 2022,
                'lokasi'          => 'Garasi Kantor Dispora Palembang',
                'jumlah'          => 1,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // KENDARAAN DINAS GABISA DIPINJEM
                'deskripsi'       => 'Kendaraan dinas angkutan barang logistik operasional Dispora. No. Polisi: BG 8934 MZ.',
                'kategori'        => 'Kendaraan Dinas (Internal)',
            ],
            [
                'nama_aset'       => 'Sepeda Motor Yamaha All New NMAX 155 VVA (BG 5401 AAZ)',
                'merk'            => 'Yamaha All New NMAX 155 VVA',
                'kode_aset'       => '120116711900000000000020241-NMX',
                'tahun_pengadaan' => 2024,
                'lokasi'          => 'Parkir Khusus Kantor Dispora',
                'jumlah'          => 1,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // KENDARAAN DINAS GABISA DIPINJEM
                'deskripsi'       => 'Sepeda motor operasional Bidang Perencanaan dan Pelaporan Dispora. No. Polisi: BG 5401 AAZ.',
                'kategori'        => 'Kendaraan Dinas (Internal)',
            ],
            [
                'nama_aset'       => 'Sepeda Motor Yamaha Freego Connected (9 Unit)',
                'merk'            => 'Yamaha Freego Connected 125',
                'kode_aset'       => '120116711900000000000020251-FRG',
                'tahun_pengadaan' => 2025,
                'lokasi'          => 'Parkir Khusus Kantor Dispora',
                'jumlah'          => 9,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // KENDARAAN DINAS GABISA DIPINJEM
                'deskripsi'       => 'Sepeda motor dinas operasional staf Dispora (Keuangan, Pemuda, Olahraga, Umum). Nopol: BG 5528-5531 & 5727-5731.',
                'kategori'        => 'Kendaraan Dinas (Internal)',
            ],

            // =================================================================
            // 6. INVENTARIS SARANA KANTOR (Internal - Tidak Bisa Dipinjam)
            // =================================================================
            [
                'nama_aset'       => 'Lemari Kayu Arsip Kantor Dispora',
                'merk'            => 'Kayu Jati Solid 2 Pintu',
                'kode_aset'       => '120116711900000000000020091-LMR01',
                'tahun_pengadaan' => 2009,
                'lokasi'          => 'Ruang Tata Usaha & Kearsipan Dispora',
                'jumlah'          => 6,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // INVENTARIS KANTOR GABISA DIPINJEM
                'deskripsi'       => 'Lemari kayu inventaris dokumen dan berkas penting Dispora Palembang.',
                'kategori'        => 'Inventaris Sarana Kantor (Internal)',
            ],
            [
                'nama_aset'       => 'Lemari Besi / Metal Penyimpan Berkas Rahasia',
                'merk'            => 'Lion Steel Cabinet',
                'kode_aset'       => '120116711900000000000020171-LMR02',
                'tahun_pengadaan' => 2017,
                'lokasi'          => 'Ruang Keuangan & Aset Dispora',
                'jumlah'          => 5,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // INVENTARIS KANTOR GABISA DIPINJEM
                'deskripsi'       => 'Lemari besi anti api untuk penyimpanan arsip SPJ dan aset dinas.',
                'kategori'        => 'Inventaris Sarana Kantor (Internal)',
            ],
            [
                'nama_aset'       => 'Filling Cabinet 4 Laci Brother',
                'merk'            => 'Brother Steel 4 Drawers',
                'kode_aset'       => '120116711900000000000020181-FLC01',
                'tahun_pengadaan' => 2018,
                'lokasi'          => 'Ruang Tata Usaha Dispora',
                'jumlah'          => 5,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // INVENTARIS KANTOR GABISA DIPINJEM
                'deskripsi'       => 'Kabinet 4 laci tarik sistem central lock untuk dokumen pegawaian.',
                'kategori'        => 'Inventaris Sarana Kantor (Internal)',
            ],
            [
                'nama_aset'       => 'Partisi Ruang Kerja Sekat Kantor',
                'merk'            => 'Modera Partition Panel',
                'kode_aset'       => '120116711900000000000020211-PRT01',
                'tahun_pengadaan' => 2021,
                'lokasi'          => 'Lantai 2 Kantor Dispora',
                'jumlah'          => 18,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // INVENTARIS KANTOR GABISA DIPINJEM
                'deskripsi'       => 'Sekat partisi permanen antar meja kerja bidang Dispora.',
                'kategori'        => 'Inventaris Sarana Kantor (Internal)',
            ],
            [
                'nama_aset'       => 'Air Conditioner (A.C. Split) Panasonic Dinding',
                'merk'            => 'Panasonic Inverter 2 PK',
                'kode_aset'       => '120116711900000000000020241-AC01',
                'tahun_pengadaan' => 2024,
                'lokasi'          => 'Ruang Kerja & Rapat Kantor Dispora',
                'jumlah'          => 8,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // INVENTARIS KANTOR GABISA DIPINJEM
                'deskripsi'       => 'Unit pendingin ruangan terpasang di dinding gedung kantor Dispora.',
                'kategori'        => 'Inventaris Sarana Kantor (Internal)',
            ],
            [
                'nama_aset'       => 'Dispenser Galon Bawah Panasonic',
                'merk'            => 'Panasonic NY-WDB83MA',
                'kode_aset'       => '120116711900000000000020231-DSP01',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Setiap Bidang & Ruang Rapat Kantor Dispora',
                'jumlah'          => 8,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // INVENTARIS KANTOR GABISA DIPINJEM
                'deskripsi'       => 'Fasilitas air minum kantor Dispora.',
                'kategori'        => 'Inventaris Sarana Kantor (Internal)',
            ],
            [
                'nama_aset'       => 'Mesin Absensi Time Recorder Solution',
                'merk'            => 'Solution X105 / X606-S',
                'kode_aset'       => '120116711900000000000020231-ABS01',
                'tahun_pengadaan' => 2023,
                'lokasi'          => 'Lobi Utama Kantor Dispora',
                'jumlah'          => 2,
                'kondisi'         => 'layak_pakai',
                'status'          => 'tersedia',
                'bisa_dipinjam'   => false, // INVENTARIS KANTOR GABISA DIPINJEM
                'deskripsi'       => 'Mesin absensi fingerprint & face recognition pegawai ASN Dispora.',
                'kategori'        => 'Inventaris Sarana Kantor (Internal)',
            ],
        ];

        foreach ($asets as $data) {
            $kategoriName = $data['kategori'];
            unset($data['kategori']);

            $data['kategori_id'] = $kategoris[$kategoriName] ?? 1;

            Aset::create($data);
        }

        $totalAset = count($asets);
        $totalPinjam = collect($asets)->where('bisa_dipinjam', true)->count();
        $totalInternal = collect($asets)->where('bisa_dipinjam', false)->count();

        $this->command->info("✓ {$totalAset} data aset riil KIB B Dispora berhasil di-seed.");
        $this->command->info("  - {$totalPinjam} aset kategori Bisa Dipinjam.");
        $this->command->info("  - {$totalInternal} aset kategori Internal (Kendaraan Dinas & Sarana Kantor).");
    }
}
