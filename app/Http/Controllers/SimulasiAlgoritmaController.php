<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Peminjaman;
use App\Services\PrioritySchedulingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SimulasiAlgoritmaController extends Controller
{
    public function __construct(
        private readonly PrioritySchedulingService $priorityService
    ) {}

    /**
     * Halaman Interaktif Lab Simulasi & Komparasi Algoritma
     */
    public function index(Request $request): View
    {
        $skenario = $request->query('skenario', '1');
        $kapasitasAset = (int) $request->query('kapasitas', 1);

        // Kasus-kasus skenario uji untuk penguji / seminar
        $daftarKasus = [
            '1' => [
                'judul' => 'Skenario 1: Bentrok Instansi Pemerintah vs Masyarakat Umum',
                'deskripsi' => 'Pengujian ketika pemohon umum mendaftar lebih awal (jam 08:00), namun instansi pemerintah dengan agenda resmi mendesak mendaftar setelahnya (jam 09:00).',
                'kapasitas' => 1,
                'data' => [
                    [
                        'id'              => 1,
                        'nama_peminjam'   => 'Budi Santoso (Masyarakat Umum)',
                        'jenis_peminjam'  => 'umum',
                        'waktu_pengajuan' => '2026-09-08 08:00:00',
                        'jumlah_pinjam'   => 1,
                        'keperluan'       => 'Peminjaman acara syukuran keluarga',
                    ],
                    [
                        'id'              => 2,
                        'nama_peminjam'   => 'Dinas Pemuda & Olahraga Prov. Sumsel',
                        'jenis_peminjam'  => 'pemerintah',
                        'waktu_pengajuan' => '2026-09-08 09:15:00',
                        'jumlah_pinjam'   => 1,
                        'keperluan'       => 'Pelaksanaan Rapat Koordinasi Pembinaan Atlet PON',
                    ],
                ]
            ],
            '2' => [
                'judul' => 'Skenario 2: Persaingan 3 Tingkatan Prioritas (Pemerintah, Organisasi, Umum)',
                'deskripsi' => 'Pengujian 3 permohonan bersaing merebut 1 unit aset fasilitas (contoh: Sound System / Proyektor Utama).',
                'kapasitas' => 1,
                'data' => [
                    [
                        'id'              => 1,
                        'nama_peminjam'   => 'Warga Komunitas Seni Palembang',
                        'jenis_peminjam'  => 'umum',
                        'waktu_pengajuan' => '2026-09-08 07:30:00',
                        'jumlah_pinjam'   => 1,
                        'keperluan'       => 'Latihan pentas seni mandiri',
                    ],
                    [
                        'id'              => 2,
                        'nama_peminjam'   => 'KNPI (Komite Nasional Pemuda Indonesia)',
                        'jenis_peminjam'  => 'organisasi',
                        'waktu_pengajuan' => '2026-09-08 08:10:00',
                        'jumlah_pinjam'   => 1,
                        'keperluan'       => 'Seminar Kepemudaan Daerah',
                    ],
                    [
                        'id'              => 3,
                        'nama_peminjam'   => 'Sekretariat Daerah Kota Palembang',
                        'jenis_peminjam'  => 'pemerintah',
                        'waktu_pengajuan' => '2026-09-08 09:00:00',
                        'jumlah_pinjam'   => 1,
                        'keperluan'       => 'Upacara Peringatan Hari Olahraga Nasional',
                    ],
                ]
            ],
            '3' => [
                'judul' => 'Skenario 3: Alokasi Kuota Kuantitas Banyak (Stok 50 Kursi Acara)',
                'deskripsi' => 'Pengujian ketika beberapa pihak mengajukan jumlah unit banyak yang totalnya melebihi stok fisik tersedia (Total permintaan 70 unit > Stok 50 unit).',
                'kapasitas' => 50,
                'data' => [
                    [
                        'id'              => 1,
                        'nama_peminjam'   => 'Panitia Turnamen Futsal Pemuda',
                        'jenis_peminjam'  => 'organisasi',
                        'waktu_pengajuan' => '2026-09-08 08:00:00',
                        'jumlah_pinjam'   => 25,
                        'keperluan'       => 'Kursi tribun penonton kompetisi',
                    ],
                    [
                        'id'              => 2,
                        'nama_peminjam'   => 'Warga Komplek Dispora',
                        'jenis_peminjam'  => 'umum',
                        'waktu_pengajuan' => '2026-09-08 08:30:00',
                        'jumlah_pinjam'   => 25,
                        'keperluan'       => 'Tenda arisan bulanan',
                    ],
                    [
                        'id'              => 3,
                        'nama_peminjam'   => 'Dinas Pariwisata Kota Palembang',
                        'jenis_peminjam'  => 'pemerintah',
                        'waktu_pengajuan' => '2026-09-08 09:45:00',
                        'jumlah_pinjam'   => 35,
                        'keperluan'       => 'Penyambutan Tamu Delegasi Pariwisata Festival Sriwijaya',
                    ],
                ]
            ],
        ];

        $kasusAktif = $daftarKasus[$skenario] ?? $daftarKasus['1'];
        $kapasitasTerpakai = $request->filled('kapasitas') ? $kapasitasAset : $kasusAktif['kapasitas'];

        // Jalankan perbandingan algoritma
        $hasil = $this->priorityService->bandingkanAlgoritma($kasusAktif['data'], $kapasitasTerpakai);

        return view('simulasi-algoritma.index', [
            'daftarKasus' => $daftarKasus,
            'skenario'    => $skenario,
            'kasusAktif'  => $kasusAktif,
            'hasil'       => $hasil,
        ]);
    }
}
