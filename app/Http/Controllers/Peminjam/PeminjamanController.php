<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Surat;
use App\Services\PrioritySchedulingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PeminjamanController extends Controller
{
    /**
     * Riwayat dan daftar peminjaman milik user yang login.
     */
    public function index(Request $request): View
    {
        $query = Peminjaman::with('aset')
            ->where('peminjam_id', Auth::id())
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->paginate(10)->withQueryString();

        return view('peminjam.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Form pengajuan peminjaman baru.
     * Support pre-select aset_id dari halaman pencarian.
     */
    public function create(Request $request): View
    {
        // Hanya muat aset yang BISA DIPINJAM, layak pakai, dan memiliki sisa stok > 0
        $asets = Aset::bisaDipakai()
            ->with(['kategori', 'peminjamans'])
            ->get()
            ->filter(fn ($a) => $a->stok_tersedia > 0)
            ->sortBy('nama_aset')
            ->values();

        // Pre-select aset jika datang dari halaman pencarian
        $selectedAset = $request->query('aset_id')
            ? Aset::bisaDipakai()->find($request->query('aset_id'))
            : null;

        return view('peminjam.peminjaman.create', compact('asets', 'selectedAset'));
    }

    /**
     * Simpan pengajuan peminjaman baru.
     * Priority score otomatis diambil dari data user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'aset_id'           => ['required', 'exists:asets,id'],
            'jumlah_pinjam'     => ['required', 'integer', 'min:1'],
            'tanggal_pinjam'    => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali'   => ['required', 'date', 'after:tanggal_pinjam'],
            'keperluan'         => ['required', 'string', 'min:20'],
            'dokumen_pendukung' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $aset = Aset::findOrFail($validated['aset_id']);

        // Validasi aset boleh dipinjam publik
        if (! $aset->bisa_dipinjam) {
            return back()->with('error', "Aset '{$aset->nama_aset}' merupakan inventaris internal dan tidak diperkenankan untuk dipinjam.");
        }

        // Validasi aset layak pakai dan stok tersedia
        if (! $aset->isAvailable() || $aset->stok_tersedia <= 0) {
            return back()->with('error', "Maaf, sisa stok aset '{$aset->nama_aset}' saat ini sedang kosong/penuh dipinjam.");
        }

        // Validasi jumlah yang dipinjam tidak melebihi stok yang ada
        if ($validated['jumlah_pinjam'] > $aset->stok_tersedia) {
            return back()->withInput()->with('error', "Jumlah unit yang dipinjam ({$validated['jumlah_pinjam']} unit) melebihi sisa stok yang tersedia ({$aset->stok_tersedia} unit).");
        }

        $user          = Auth::user();
        $priorityScore = $user->priority_score;

        // Generate nomor pengajuan
        $nomorPengajuan = $this->generateNomorPengajuan();

        $dokumenPath = null;
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen-peminjaman', 'public');
        }

        $peminjaman = Peminjaman::create([
            'peminjam_id'      => $user->id,
            'aset_id'          => $aset->id,
            'jumlah_pinjam'    => $validated['jumlah_pinjam'],
            'nomor_pengajuan'  => $nomorPengajuan,
            'tanggal_pinjam'   => $validated['tanggal_pinjam'],
            'tanggal_kembali'  => $validated['tanggal_kembali'],
            'keperluan'        => $validated['keperluan'],
            'dokumen_pendukung' => $dokumenPath,
            'priority_score'   => $priorityScore,
            'status'           => 'pending',
        ]);

        // Kirim notifikasi ke semua pengelola
        $this->notifikasiPengelola($peminjaman);

        return redirect()->route('peminjam.peminjaman.show', $peminjaman)
            ->with('success', "Pengajuan berhasil dibuat dengan nomor {$nomorPengajuan}. Silakan tunggu verifikasi dari pengelola.");
    }

    /**
     * Detail satu peminjaman milik user yang login.
     */
    public function show(Peminjaman $peminjaman): View
    {
        // Pastikan peminjaman ini milik user yang login
        abort_if($peminjaman->peminjam_id !== Auth::id(), 403);

        $peminjaman->load(['aset.kategori', 'surat', 'pengembalian', 'keluhanss']);

        return view('peminjam.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Peminjam membatalkan pengajuan (hanya jika masih pending).
     */
    public function batalkan(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        abort_if($peminjaman->peminjam_id !== Auth::id(), 403);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pengajuan yang sudah diproses tidak dapat dibatalkan.');
        }

        $request->validate([
            'alasan_pembatalan' => ['required', 'string'],
        ]);

        $peminjaman->update([
            'status'           => 'dibatalkan',
            'alasan_penolakan' => $request->alasan_pembatalan,
        ]);

        return redirect()->route('peminjam.peminjaman.index')
            ->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    /**
     * Download surat pernyataan peminjaman (PDF).
     */
    public function downloadSurat(Peminjaman $peminjaman): StreamedResponse|RedirectResponse
    {
        abort_if($peminjaman->peminjam_id !== Auth::id(), 403);

        if (! $peminjaman->surat || ! $peminjaman->surat->file_path) {
            return back()->with('error', 'Surat pernyataan belum tersedia.');
        }

        $path = $peminjaman->surat->file_path;

        if (! Storage::disk('public')->exists($path)) {
            return back()->with('error', 'File surat tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $path,
            "surat-{$peminjaman->nomor_pengajuan}.pdf"
        );
    }

    /**
     * Halaman scan barcode — verifikasi identitas peminjam (auth-gated).
     * Hanya bisa diakses oleh user yang sudah login.
     */
    public function scanBarcode(string $token): View
    {
        $surat = Surat::where('barcode_token', $token)->firstOrFail();

        // Catat waktu akses barcode
        $surat->update(['barcode_diakses_at' => now()]);

        $peminjaman = $surat->peminjaman()->with('peminjam', 'aset')->firstOrFail();

        return view('surat.scan-result', compact('surat', 'peminjaman'));
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /**
     * Generate nomor pengajuan unik: PJM-YYYY-XXXX
     */
    private function generateNomorPengajuan(): string
    {
        $tahun  = now()->year;
        $urutan = Peminjaman::whereYear('created_at', $tahun)->count() + 1;
        $nomor  = str_pad($urutan, 4, '0', STR_PAD_LEFT);

        return "PJM-{$tahun}-{$nomor}";
    }

    /**
     * Kirim notifikasi ke semua pengelola bahwa ada pengajuan baru.
     */
    private function notifikasiPengelola(Peminjaman $peminjaman): void
    {
        $pengelolas = \App\Models\User::pengelola()->aktif()->get();

        foreach ($pengelolas as $pengelola) {
            Notifikasi::create([
                'user_id'       => $pengelola->id,
                'peminjaman_id' => $peminjaman->id,
                'judul'         => 'Pengajuan Pinjaman Baru',
                'pesan'         => "Ada pengajuan pinjaman baru (No. {$peminjaman->nomor_pengajuan}) dari {$peminjaman->peminjam->name} untuk aset {$peminjaman->aset->nama_aset}.",
                'tipe'          => 'pengajuan',
            ]);
        }
    }
}
