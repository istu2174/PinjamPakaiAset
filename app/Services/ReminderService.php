<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * ReminderService
 *
 * Mengelola pengiriman notifikasi reminder otomatis kepada peminjam.
 * Reminder dikirim dari H-7 sampai H-0 (hari-H peminjaman).
 * Dipanggil oleh scheduled command setiap hari.
 */
class ReminderService
{
    /**
     * Proses reminder untuk semua peminjaman yang aktif.
     * Method ini dipanggil oleh KirimReminderCommand setiap hari jam 08.00.
     *
     * @return array{terkirim: int, dilewati: int}
     */
    public function prosesHarian(): array
    {
        $terkirim = 0;
        $dilewati = 0;

        $today = Carbon::today();

        // Ambil semua peminjaman yang disetujui atau sedang dipinjam
        // dalam rentang yang relevan (mulai hari ini sampai 7 hari ke depan)
        $peminjamans = Peminjaman::with('peminjam', 'aset')
            ->whereIn('status', ['disetujui', 'dipinjam'])
            ->where(function ($q) use ($today) {
                $q
                    // Reminder sebelum pinjam: H-7 sampai H-0 tanggal_pinjam
                    ->whereBetween('tanggal_pinjam', [
                        $today->copy(),
                        $today->copy()->addDays(7),
                    ])
                    // Reminder sebelum kembali: H-7 sampai H-0 tanggal_kembali
                    ->orWhereBetween('tanggal_kembali', [
                        $today->copy(),
                        $today->copy()->addDays(7),
                    ]);
            })
            ->get();

        foreach ($peminjamans as $peminjaman) {
            $dikirim = $this->kirimReminderUntukPeminjaman($peminjaman, $today);

            if ($dikirim) {
                $terkirim++;
            } else {
                $dilewati++;
            }
        }

        return compact('terkirim', 'dilewati');
    }

    /**
     * Kirim reminder untuk satu peminjaman.
     * Cek apakah reminder hari ini sudah pernah dikirim untuk menghindari duplikasi.
     *
     * @param  Peminjaman $peminjaman
     * @param  Carbon     $today
     * @return bool  true jika berhasil dikirim, false jika dilewati
     */
    private function kirimReminderUntukPeminjaman(Peminjaman $peminjaman, Carbon $today): bool
    {
        $pesanList = [];

        // --- Cek reminder tanggal pinjam ---
        $hariMenujuPinjam = $today->diffInDays($peminjaman->tanggal_pinjam, false);

        if ($hariMenujuPinjam >= 0 && $hariMenujuPinjam <= 7 && $peminjaman->status === 'disetujui') {
            $pesanList[] = $this->buatPesanReminderPinjam($peminjaman, (int) $hariMenujuPinjam);
        }

        // --- Cek reminder tanggal kembali ---
        $hariMenujuKembali = $today->diffInDays($peminjaman->tanggal_kembali, false);

        if ($hariMenujuKembali >= 0 && $hariMenujuKembali <= 7 && $peminjaman->status === 'dipinjam') {
            $pesanList[] = $this->buatPesanReminderKembali($peminjaman, (int) $hariMenujuKembali);
        }

        if (empty($pesanList)) {
            return false;
        }

        foreach ($pesanList as $pesan) {
            // Cek duplikasi: jangan kirim jika sudah ada notif hari ini dengan judul yang sama
            $sudahAda = Notifikasi::where('user_id', $peminjaman->peminjam_id)
                ->where('peminjaman_id', $peminjaman->id)
                ->where('tipe', 'reminder')
                ->where('judul', $pesan['judul'])
                ->whereDate('created_at', $today)
                ->exists();

            if (! $sudahAda) {
                Notifikasi::create([
                    'user_id'       => $peminjaman->peminjam_id,
                    'peminjaman_id' => $peminjaman->id,
                    'judul'         => $pesan['judul'],
                    'pesan'         => $pesan['pesan'],
                    'tipe'          => 'reminder',
                ]);
            }
        }

        return true;
    }

    /**
     * Buat pesan reminder untuk tanggal pengambilan barang.
     */
    private function buatPesanReminderPinjam(Peminjaman $peminjaman, int $hariLagi): array
    {
        $namaAset    = $peminjaman->aset->nama_aset;
        $tanggal     = $peminjaman->tanggal_pinjam->format('d/m/Y');
        $noPengajuan = $peminjaman->nomor_pengajuan;

        if ($hariLagi === 0) {
            return [
                'judul' => "Hari Ini — Pengambilan Aset {$namaAset}",
                'pesan' => "Pengambilan aset **{$namaAset}** (No. Pengajuan: {$noPengajuan}) dijadwalkan HARI INI, {$tanggal}. Harap datang ke Dinas Kepemudaan dan Olahraga untuk mengambil aset sesuai surat pernyataan.",
            ];
        }

        return [
            'judul' => "Pengingat H-{$hariLagi} — Pengambilan Aset {$namaAset}",
            'pesan' => "Pengambilan aset **{$namaAset}** (No. Pengajuan: {$noPengajuan}) dijadwalkan {$hariLagi} hari lagi, pada tanggal {$tanggal}. Pastikan Anda hadir tepat waktu dan membawa dokumen yang diperlukan.",
        ];
    }

    /**
     * Buat pesan reminder untuk tanggal pengembalian barang.
     */
    private function buatPesanReminderKembali(Peminjaman $peminjaman, int $hariLagi): array
    {
        $namaAset    = $peminjaman->aset->nama_aset;
        $tanggal     = $peminjaman->tanggal_kembali->format('d/m/Y');
        $noPengajuan = $peminjaman->nomor_pengajuan;

        if ($hariLagi === 0) {
            return [
                'judul' => "Hari Ini — Batas Pengembalian Aset {$namaAset}",
                'pesan' => "Pengembalian aset **{$namaAset}** (No. Pengajuan: {$noPengajuan}) batas akhirnya HARI INI, {$tanggal}. Segera kembalikan aset ke Dinas Kepemudaan dan Olahraga untuk menghindari keterlambatan.",
            ];
        }

        return [
            'judul' => "Pengingat H-{$hariLagi} — Pengembalian Aset {$namaAset}",
            'pesan' => "Aset **{$namaAset}** (No. Pengajuan: {$noPengajuan}) harus dikembalikan {$hariLagi} hari lagi, pada tanggal {$tanggal}. Harap persiapkan pengembalian tepat waktu.",
        ];
    }
}
