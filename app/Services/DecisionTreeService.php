<?php

namespace App\Services;

/**
 * DecisionTreeService
 *
 * Implementasi algoritma Decision Tree untuk mengklasifikasikan
 * kelayakan kondisi aset milik Dinas Kepemudaan dan Olahraga.
 *
 * Hasil klasifikasi:
 *   - layak_pakai     : Aset dalam kondisi baik, siap dipinjamkan
 *   - perlu_perawatan : Aset perlu maintenance sebelum bisa dipinjam
 *   - layak_lelang    : Aset sudah tidak layak, harus dilelang/dihapus
 *
 * Pohon Keputusan:
 *   1. Apakah kondisi fisik baik (>=3)?
 *      ├── YA  → Apakah usia aset < 10 tahun?
 *      │         ├── YA  → layak_pakai
 *      │         └── TIDAK → Apakah riwayat perawatan rutin?
 *      │                     ├── YA  → layak_pakai
 *      │                     └── TIDAK → perlu_perawatan
 *      └── TIDAK → Apakah masih bisa diperbaiki?
 *                  ├── YA  → perlu_perawatan
 *                  └── TIDAK → layak_lelang
 */
class DecisionTreeService
{
    // Konstanta threshold
    private const KONDISI_FISIK_MINIMUM   = 3;   // Skala 1-5
    private const USIA_MAKS_TANPA_REVIEW  = 10;  // Tahun

    /**
     * Klasifikasikan kondisi aset berdasarkan kriteria yang diberikan.
     *
     * @param  array{
     *   kondisi_fisik: int,
     *   usia_aset: int,
     *   riwayat_perawatan_rutin: bool,
     *   frekuensi_penggunaan: int,
     *   masih_bisa_diperbaiki: bool,
     * } $kriteria
     * @return array{hasil: string, jalur: array, keterangan: string}
     */
    public function klasifikasi(array $kriteria): array
    {
        $kondisiFisik           = (int) $kriteria['kondisi_fisik'];
        $usiaAset               = (int) $kriteria['usia_aset'];
        $riwayatPerawatanRutin  = (bool) $kriteria['riwayat_perawatan_rutin'];
        $masihBisaDiperbaiki    = (bool) $kriteria['masih_bisa_diperbaiki'];

        $jalur = [];


        // --- Node 1: Cek kondisi fisik ---
        if ($kondisiFisik >= self::KONDISI_FISIK_MINIMUM) {
            $jalur[] = "Kondisi fisik baik (skor: {$kondisiFisik}/5) → YA";

            // --- Node 2: Cek usia aset ---
            if ($usiaAset < self::USIA_MAKS_TANPA_REVIEW) {
                $jalur[] = "Usia aset {$usiaAset} tahun (< " . self::USIA_MAKS_TANPA_REVIEW . " tahun) → YA";
                return $this->hasilLayakPakai($jalur, "Kondisi fisik baik dan usia aset masih muda.");
            }

            $jalur[] = "Usia aset {$usiaAset} tahun (≥ " . self::USIA_MAKS_TANPA_REVIEW . " tahun) → TIDAK";

            // --- Node 3: Cek riwayat perawatan ---
            if ($riwayatPerawatanRutin) {
                $jalur[] = "Riwayat perawatan rutin → YA";
                return $this->hasilLayakPakai($jalur, "Walaupun aset tua, perawatan rutin menjaga kondisinya tetap baik.");
            }

            $jalur[] = "Riwayat perawatan rutin → TIDAK";
            return $this->hasilPerluPerawatan($jalur, "Usia aset sudah tua dan tidak ada riwayat perawatan rutin.");
        }

        // --- Node 1 Cabang TIDAK ---
        $jalur[] = "Kondisi fisik kurang baik (skor: {$kondisiFisik}/5) → TIDAK";

        // --- Node 4: Cek bisa diperbaiki ---
        if ($masihBisaDiperbaiki) {
            $jalur[] = "Masih bisa diperbaiki → YA";
            return $this->hasilPerluPerawatan($jalur, "Kondisi fisik buruk namun aset masih bisa diperbaiki/dirawat.");
        }

        $jalur[] = "Masih bisa diperbaiki → TIDAK";
        return $this->hasilLayakLelang($jalur, "Kondisi fisik buruk dan aset tidak bisa diperbaiki lagi.");
    }


    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function hasilLayakPakai(array $jalur, string $keterangan): array
    {
        return [
            'hasil'      => 'layak_pakai',
            'label'      => 'Layak Pakai',
            'jalur'      => $jalur,
            'keterangan' => $keterangan,
        ];
    }

    private function hasilPerluPerawatan(array $jalur, string $keterangan): array
    {
        return [
            'hasil'      => 'perlu_perawatan',
            'label'      => 'Perlu Perawatan',
            'jalur'      => $jalur,
            'keterangan' => $keterangan,
        ];
    }

    private function hasilLayakLelang(array $jalur, string $keterangan): array
    {
        return [
            'hasil'      => 'layak_lelang',
            'label'      => 'Layak Lelang',
            'jalur'      => $jalur,
            'keterangan' => $keterangan,
        ];
    }
}
