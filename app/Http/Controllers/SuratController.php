<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\SuratService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * SuratController
 *
 * Menyajikan file PDF surat pernyataan langsung dari disk 'public' melalui
 * controller (bukan lewat URL /storage/...), sehingga tidak bergantung pada
 * symlink public/storage dan akses tetap dibatasi sesuai role:
 *   - pengelola & admin : boleh melihat semua surat
 *   - peminjam          : hanya surat miliknya sendiri
 */
class SuratController extends Controller
{
    public function __construct(
        private readonly SuratService $suratService
    ) {}

    /**
     * Tampilkan PDF surat di browser (inline). Tambahkan ?download=1 untuk mengunduh.
     */
    public function show(Peminjaman $peminjaman): Response
    {
        $user = Auth::user();

        $boleh = in_array($user->role, ['pengelola', 'admin'], true)
            || $peminjaman->peminjam_id === $user->id;

        abort_unless($boleh, 403);

        if (! in_array($peminjaman->status, ['disetujui', 'dipinjam', 'selesai'], true)) {
            abort(404, 'Surat pernyataan belum tersedia untuk peminjaman ini.');
        }

        $surat = $peminjaman->surat;
        $disk  = Storage::disk('public');

        // Jika surat belum ada / file hilang → buat ulang otomatis
        if (! $surat || ! $surat->file_path || ! $disk->exists($surat->file_path)) {
            $surat = $this->suratService->generate($peminjaman);
        }

        $namaFile = "surat-{$peminjaman->nomor_pengajuan}.pdf";
        $headers  = ['Content-Type' => 'application/pdf'];

        if (request()->boolean('download')) {
            return $disk->download($surat->file_path, $namaFile, $headers);
        }

        return response()->file($disk->path($surat->file_path), $headers + [
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }
}
