<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

/**
 * SuratService
 *
 * Mengelola pembuatan surat pernyataan peminjaman aset dalam format PDF.
 * Setiap surat dilengkapi dengan QR Code yang berisi barcode_token unik.
 * QR Code hanya dapat diakses setelah login (auth-gated).
 */
class SuratService
{
    /**
     * Generate surat pernyataan untuk peminjaman yang sudah disetujui.
     * Membuat record di tabel surats dan generate file PDF.
     *
     * @param  Peminjaman $peminjaman
     * @return Surat
     */
    public function generate(Peminjaman $peminjaman): Surat
    {
        // Jika surat sudah ada, regenerate
        if ($peminjaman->surat) {
            return $this->regenerate($peminjaman->surat, $peminjaman);
        }

        $nomorSurat    = $this->generateNomorSurat();
        $barcodeToken  = Str::random(64);
        $filePath      = $this->generatePdf($peminjaman, $nomorSurat, $barcodeToken);

        return Surat::create([
            'peminjaman_id' => $peminjaman->id,
            'nomor_surat'   => $nomorSurat,
            'tanggal_surat' => now()->toDateString(),
            'file_path'     => $filePath,
            'barcode_token' => $barcodeToken,
        ]);
    }

    /**
     * Regenerate PDF surat yang sudah ada (misal: ada perubahan data).
     *
     * @param  Surat      $surat
     * @param  Peminjaman $peminjaman
     * @return Surat
     */
    public function regenerate(Surat $surat, Peminjaman $peminjaman): Surat
    {
        $filePath = $this->generatePdf($peminjaman, $surat->nomor_surat, $surat->barcode_token);

        $surat->update(['file_path' => $filePath]);

        return $surat->fresh();
    }

    /**
     * Generate nomor surat dalam format: XXX/SIPA/DISPORA/YYYY
     *
     * @return string
     */
    private function generateNomorSurat(): string
    {
        $tahun   = now()->year;
        $urutan  = Surat::whereYear('created_at', $tahun)->count() + 1;
        $nomor   = str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return "{$nomor}/SIPA/DISPORA/{$tahun}";
    }

    /**
     * Generate file PDF menggunakan DomPDF dan simpan ke storage.
     *
     * @param  Peminjaman $peminjaman
     * @param  string     $nomorSurat
     * @param  string     $barcodeToken
     * @return string     Path file yang disimpan
     */
    private function generatePdf(Peminjaman $peminjaman, string $nomorSurat, string $barcodeToken): string
    {
        $peminjaman->load(['peminjam', 'aset.kategori', 'disetujuiOleh']);

        // Generate QR Code sebagai base64 image
        $barcodeUrl = route('surat.scan', $barcodeToken);
        $qrCodeBase64 = $this->generateQrCode($barcodeUrl);

        $pdf = Pdf::loadView('pdf.surat-peminjaman', [
            'peminjaman'    => $peminjaman,
            'nomorSurat'    => $nomorSurat,
            'tanggalSurat'  => now()->locale('id')->translatedFormat('d F Y'),
            'qrCodeBase64'  => $qrCodeBase64,
            'barcodeUrl'    => $barcodeUrl,
            'barcodeToken'  => $barcodeToken,
        ])->setPaper('a4', 'portrait');

        $fileName  = "surat-{$peminjaman->nomor_pengajuan}.pdf";
        $directory = storage_path('app/public/surats');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf->save("{$directory}/{$fileName}");

        return "surats/{$fileName}";
    }

    /**
     * Generate QR Code dan kembalikan sebagai string base64 untuk embed di PDF.
     *
     * @param  string $url
     * Menggunakan format SVG agar tidak membutuhkan ekstensi imagick
     * (format PNG pada bacon-qr-code wajib imagick). DomPDF mendukung SVG.
     *
     * @return string  Base64 encoded SVG image
     */
    private function generateQrCode(string $url): string
    {
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(150)
            ->margin(1)
            ->generate($url);

        return 'data:image/svg+xml;base64,' . base64_encode((string) $qrCode);
    }
}
