<?php

namespace App\Services;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * PrioritySchedulingService
 *
 * Implementasi algoritma Priority Scheduling untuk menyelesaikan
 * konflik jadwal peminjaman aset.
 *
 * Sistem Prioritas:
 *   - Skor 3 (Tinggi)  : Instansi Pemerintah / Dinas
 *   - Skor 2 (Sedang)  : Organisasi Kepemudaan / Olahraga
 *   - Skor 1 (Rendah)  : Masyarakat Umum
 *
 * Aturan:
 *   - Jika ada konflik tanggal pada aset yang sama → bandingkan skor prioritas
 *   - Skor lebih tinggi → didahulukan
 *   - Jika skor sama → FCFS (First Come First Served berdasarkan created_at)
 */
class PrioritySchedulingService
{
    /**
     * Cek apakah ada konflik jadwal untuk aset tertentu pada rentang tanggal tertentu.
     *
     * @param  int    $asetId         ID aset yang ingin dipinjam
     * @param  string $tanggalPinjam  Tanggal mulai pinjam (Y-m-d)
     * @param  string $tanggalKembali Tanggal kembali (Y-m-d)
     * @param  int|null $kecualiId   ID peminjaman yang dikecualikan (untuk update)
     * @return Collection            Daftar peminjaman yang konflik
     */
    public function cekKonflik(
        int $asetId,
        string $tanggalPinjam,
        string $tanggalKembali,
        ?int $kecualiId = null
    ): Collection {
        $query = Peminjaman::where('aset_id', $asetId)
            ->whereNotIn('status', ['ditolak', 'dibatalkan', 'selesai'])
            ->where(function ($q) use ($tanggalPinjam, $tanggalKembali) {
                // Overlap: (StartA <= EndB) AND (EndA >= StartB)
                $q->where('tanggal_pinjam', '<=', $tanggalKembali)
                  ->where('tanggal_kembali', '>=', $tanggalPinjam);
            });

        if ($kecualiId) {
            $query->where('id', '!=', $kecualiId);
        }

        return $query->with('peminjam')->get();
    }

    /**
     * Tentukan apakah pengajuan baru dapat diproses berdasarkan prioritas.
     * Jika ada konflik, bandingkan skor prioritas dengan peminjaman yang sudah ada.
     *
     * @param  int    $priorityScoreBaru  Skor prioritas pengajuan baru
     * @param  Collection $konflikList    Daftar peminjaman yang konflik
     * @return array{
     *   bisa_diproses: bool,
     *   status: string,
     *   keterangan: string,
     *   peminjaman_tergeser: array
     * }
     */
    public function tentukan(int $priorityScoreBaru, Collection $konflikList): array
    {
        if ($konflikList->isEmpty()) {
            return [
                'bisa_diproses'      => true,
                'status'             => 'aman',
                'keterangan'         => 'Tidak ada konflik jadwal. Aset tersedia pada tanggal yang diminta.',
                'peminjaman_tergeser' => [],
            ];
        }

        // Cari peminjaman konflik dengan prioritas tertinggi yang sudah ada
        $prioritasTertinggiYangAda = $konflikList->max('priority_score');

        if ($priorityScoreBaru > $prioritasTertinggiYangAda) {
            // Pengajuan baru punya prioritas lebih tinggi → bisa geser yang lama
            $yangTergeser = $konflikList->where('priority_score', '<', $priorityScoreBaru)->values();
            return [
                'bisa_diproses'      => true,
                'status'             => 'prioritas_lebih_tinggi',
                'keterangan'         => "Pengajuan ini memiliki prioritas lebih tinggi (skor: {$priorityScoreBaru}) dibanding peminjaman yang sudah ada (skor: {$prioritasTertinggiYangAda}). Peminjaman sebelumnya akan digeser.",
                'peminjaman_tergeser' => $yangTergeser->pluck('id')->toArray(),
            ];
        }

        if ($priorityScoreBaru === $prioritasTertinggiYangAda) {
            // Prioritas sama → FCFS: yang duluan mengajukan menang
            return [
                'bisa_diproses'      => false,
                'status'             => 'konflik_sama_prioritas',
                'keterangan'         => "Prioritas sama (skor: {$priorityScoreBaru}). Berdasarkan aturan FCFS, pengajuan sebelumnya lebih dulu dan tidak dapat digeser.",
                'peminjaman_tergeser' => [],
            ];
        }

        // Prioritas lebih rendah → tidak bisa diproses
        return [
            'bisa_diproses'      => false,
            'status'             => 'prioritas_lebih_rendah',
            'keterangan'         => "Pengajuan ini memiliki prioritas lebih rendah (skor: {$priorityScoreBaru}) dibanding peminjaman yang sudah ada (skor: {$prioritasTertinggiYangAda}). Tidak dapat diproses pada tanggal ini.",
            'peminjaman_tergeser' => [],
        ];
    }

    /**
     * Proses lengkap: cek konflik + tentukan prioritas dalam satu langkah.
     *
     * @return array{
     *   ada_konflik: bool,
     *   bisa_diproses: bool,
     *   status: string,
     *   keterangan: string,
     *   konflik_list: Collection,
     *   peminjaman_tergeser: array
     * }
     */
    public function proses(
        int $asetId,
        string $tanggalPinjam,
        string $tanggalKembali,
        int $priorityScore,
        ?int $kecualiId = null
    ): array {
        $konflikList = $this->cekKonflik($asetId, $tanggalPinjam, $tanggalKembali, $kecualiId);
        $hasil       = $this->tentukan($priorityScore, $konflikList);

        return array_merge($hasil, [
            'ada_konflik'  => $konflikList->isNotEmpty(),
            'konflik_list' => $konflikList,
        ]);
    }

    /**
     * Geser (tolak) peminjaman yang kalah prioritas.
     * Dipanggil setelah pengajuan prioritas lebih tinggi disetujui.
     *
     * @param  array $peminjamanIds  ID peminjaman yang akan digeser
     * @param  string $alasan        Alasan pengeseran
     */
    public function geserPeminjaman(array $peminjamanIds, string $alasan): void
    {
        if (empty($peminjamanIds)) {
            return;
        }

        Peminjaman::whereIn('id', $peminjamanIds)
            ->update([
                'status'           => 'ditolak',
                'alasan_penolakan' => $alasan,
                'ditolak_at'       => now(),
            ]);
    }

    /**
     * Konversi jenis peminjam ke skor prioritas.
     *
     * @param  string $jenisPeminjam  (pemerintah | organisasi | umum)
     * @return int
     */
    public static function getSkor(string $jenisPeminjam): int
    {
        return match ($jenisPeminjam) {
            'pemerintah'  => 3,
            'organisasi'  => 2,
            'umum'        => 1,
            default       => 1,
        };
    }

    /**
     * Dapatkan label prioritas berdasarkan skor.
     *
     * @param  int $skor
     * @return string
     */
    public static function getLabelPrioritas(int $skor): string
    {
        return match ($skor) {
            3       => 'Tinggi (Instansi Pemerintah)',
            2       => 'Sedang (Organisasi)',
            1       => 'Rendah (Masyarakat Umum)',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Simulasi & Komparasi langsung antara:
     * 1. Tanpa Algoritma (Konvensional - FIFO / First-Come First-Served murni)
     * 2. Dengan Algoritma (Priority Scheduling - Preemptive dengan Tie-Breaker FCFS)
     *
     * @param  array  $pengajuanList  Daftar pemohon simulasi
     * @param  int    $kapasitasAset  Kapasitas/stok barang yang diperebutkan
     * @return array
     */
    public function bandingkanAlgoritma(array $pengajuanList, int $kapasitasAset = 1): array
    {
        // 1. Simulasi Tanpa Algoritma (FIFO Konvensional)
        // Diurutkan murni berdasarkan waktu pengajuan (siapa cepat dia dapat)
        $fifoList = collect($pengajuanList)->sortBy('waktu_pengajuan')->values();
        $fifoHasil = [];
        $sisaStokFifo = $kapasitasAset;

        foreach ($fifoList as $item) {
            $skor = self::getSkor($item['jenis_peminjam'] ?? 'umum');
            $qty  = (int) ($item['jumlah_pinjam'] ?? 1);
            $diterima = $sisaStokFifo >= $qty;

            if ($diterima) {
                $sisaStokFifo -= $qty;
            }

            $fifoHasil[] = array_merge($item, [
                'priority_score'   => $skor,
                'status_keputusan' => $diterima ? 'Diterima' : 'Ditolak (Stok Habis)',
                'sisa_stok'        => max(0, $sisaStokFifo),
                'alasan'           => $diterima
                    ? 'Pengajuan lebih awal (FIFO). Kuota unit masih tersedia.'
                    : 'Kalah cepat klik mendaftar. Kuota telah terisi oleh pemohon sebelumnya.',
            ]);
        }

        // 2. Simulasi Dengan Algoritma (Priority Scheduling)
        // Diurutkan berdasarkan Skor Prioritas (DESC), jika skor sama baru waktu pendaftaran (ASC)
        $priorityList = collect($pengajuanList)->map(function ($item) {
            $item['priority_score'] = self::getSkor($item['jenis_peminjam'] ?? 'umum');
            return $item;
        })->sort(function ($a, $b) {
            if ($b['priority_score'] === $a['priority_score']) {
                return strcmp($a['waktu_pengajuan'], $b['waktu_pengajuan']);
            }
            return $b['priority_score'] <=> $a['priority_score'];
        })->values();

        $priorityHasil = [];
        $sisaStokPriority = $kapasitasAset;

        foreach ($priorityList as $item) {
            $qty = (int) ($item['jumlah_pinjam'] ?? 1);
            $diterima = $sisaStokPriority >= $qty;

            if ($diterima) {
                $sisaStokPriority -= $qty;
            }

            $priorityHasil[] = array_merge($item, [
                'status_keputusan' => $diterima ? 'Diterima (Prioritas)' : 'Ditolak / Masuk Cadangan',
                'sisa_stok'        => max(0, $sisaStokPriority),
                'alasan'           => $diterima
                    ? 'Skor prioritas ' . self::getLabelPrioritas($item['priority_score']) . ' berhak didahulukan untuk sarpras publik.'
                    : 'Kalah prioritas dibanding instansi/pemohon lain yang kepentingannya lebih mendesak.',
            ]);
        }

        // 3. Analisis Perbedaan Hasil
        $perbedaan = [];
        foreach ($pengajuanList as $item) {
            $fRes = collect($fifoHasil)->firstWhere('id', $item['id']);
            $pRes = collect($priorityHasil)->firstWhere('id', $item['id']);

            $fStatus = $fRes['status_keputusan'] ?? '';
            $pStatus = $pRes['status_keputusan'] ?? '';

            $isFPass = str_starts_with($fStatus, 'Diterima');
            $isPPass = str_starts_with($pStatus, 'Diterima');

            if ($isFPass !== $isPPass) {
                $perbedaan[] = [
                    'id'               => $item['id'],
                    'nama_peminjam'    => $item['nama_peminjam'],
                    'jenis_peminjam'   => $item['jenis_peminjam'],
                    'tanpa_algoritma'  => $fStatus,
                    'dengan_algoritma' => $pStatus,
                    'kesimpulan'       => $isPPass
                        ? "Tanpa algoritma, peminjam instansi ini DITOLAK hanya karena terlambat mendaftar. Dengan Priority Scheduling, instansi resmi berhasil DIPRIORITASKAN!"
                        : "Tanpa algoritma, peminjam umum ini DITERIMA duluan. Dengan Priority Scheduling, dialihkan karena ada instansi pemerintah dengan agenda resmi mendesak.",
                ];
            }
        }

        return [
            'kapasitas_aset'   => $kapasitasAset,
            'tanpa_algoritma'  => $fifoHasil,
            'dengan_algoritma' => $priorityHasil,
            'perbedaan'        => $perbedaan,
            'ada_perbedaan'    => count($perbedaan) > 0,
        ];
    }
}
