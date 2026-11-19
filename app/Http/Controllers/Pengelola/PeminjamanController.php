<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Services\DecisionTreeService;
use App\Services\PrioritySchedulingService;
use App\Services\SuratService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function __construct(
        private readonly PrioritySchedulingService $priorityService,
        private readonly DecisionTreeService $decisionTreeService,
        private readonly SuratService $suratService
    ) {}

    /**
     * Daftar pengajuan peminjaman yang perlu ditangani pengelola.
     */
    public function index(Request $request): View
    {
        $query = Peminjaman::with(['peminjam', 'aset'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->paginate(15)->withQueryString();

        return view('pengelola.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Detail pengajuan peminjaman — pengelola bisa lihat info Decision Tree & Priority.
     */
    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load([
            'peminjam', 'aset.kategori', 'aset.kondisiTerbaru.pengelola',
            'surat', 'pengembalian', 'keluhanss.user',
        ]);

        // Info Priority Scheduling untuk tampilan
        $prioritasInfo = PrioritySchedulingService::getLabelPrioritas($peminjaman->priority_score);

        return view('pengelola.peminjaman.show', compact('peminjaman', 'prioritasInfo'));
    }

    /**
     * Pengelola menyetujui pengajuan peminjaman (pending → disetujui).
     * Pengelola adalah satu-satunya pihak yang berwenang menyetujui/menolak peminjaman.
     * Sistem otomatis cek: kelayakan aset (Decision Tree) & konflik jadwal (Priority Scheduling),
     * lalu generate surat pernyataan PDF.
     */
    public function setujui(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        // 'diverifikasi' = data lama dari alur sebelumnya (menunggu admin) — kini diproses pengelola.
        if (! in_array($peminjaman->status, ['pending', 'diverifikasi'], true)) {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_pengelola' => ['nullable', 'string'],
        ]);

        // --- Cek 1: Kelayakan aset via Decision Tree ---
        $kondisiAset = $peminjaman->aset->kondisiTerbaru;

        if ($kondisiAset && $kondisiAset->hasil_decision_tree !== 'layak_pakai') {
            return back()->with('error',
                "Aset tidak dapat dipinjam. Hasil Decision Tree: {$kondisiAset->hasilLabel}. " .
                "Aset harus dalam kondisi 'Layak Pakai' untuk dapat dipinjam."
            );
        }

        if ($peminjaman->aset->status !== 'tersedia') {
            return back()->with('error', "Aset saat ini tidak tersedia (status: {$peminjaman->aset->status}).");
        }

        // --- Cek 2: Konflik jadwal via Priority Scheduling ---
        $hasilPriority = $this->priorityService->proses(
            asetId:        $peminjaman->aset_id,
            tanggalPinjam: $peminjaman->tanggal_pinjam->toDateString(),
            tanggalKembali: $peminjaman->tanggal_kembali->toDateString(),
            priorityScore: $peminjaman->priority_score,
            kecualiId:     $peminjaman->id
        );

        if (! $hasilPriority['bisa_diproses']) {
            return back()->with('error',
                "Konflik jadwal terdeteksi. {$hasilPriority['keterangan']}"
            );
        }

        // Jika ada peminjaman yang tergeser karena prioritas lebih rendah
        if (! empty($hasilPriority['peminjaman_tergeser'])) {
            $this->priorityService->geserPeminjaman(
                $hasilPriority['peminjaman_tergeser'],
                "Digeser oleh peminjaman dengan prioritas lebih tinggi (No. {$peminjaman->nomor_pengajuan})."
            );
        }

        // --- Update status ke disetujui + generate surat (satu transaksi) ---
        DB::transaction(function () use ($request, $peminjaman, $hasilPriority) {
            $peminjaman->update([
                'status'             => 'disetujui',
                'catatan_pengelola'  => $request->catatan_pengelola,
                'konflik_jadwal'     => $hasilPriority['ada_konflik'],
                'diverifikasi_at'    => $peminjaman->diverifikasi_at ?? now(),
                'diverifikasi_oleh'  => $peminjaman->diverifikasi_oleh ?? Auth::id(),
                'disetujui_at'       => now(),
                'disetujui_oleh'     => Auth::id(),
            ]);

            // Generate surat pernyataan PDF otomatis
            $this->suratService->generate($peminjaman->fresh());
        });

        // Kirim notifikasi ke peminjam
        Notifikasi::create([
            'user_id'       => $peminjaman->peminjam_id,
            'peminjaman_id' => $peminjaman->id,
            'judul'         => 'Peminjaman Disetujui',
            'pesan'         => "Pengajuan peminjaman Anda (No. {$peminjaman->nomor_pengajuan}) telah disetujui oleh pengelola aset. Surat pernyataan sudah dapat Anda unduh.",
            'tipe'          => 'persetujuan',
        ]);

        return redirect()->route('pengelola.peminjaman.show', $peminjaman)
            ->with('success', 'Peminjaman disetujui dan surat pernyataan PDF berhasil dibuat.');
    }

    /**
     * Buat ulang file PDF surat (misal file hilang atau perlu diperbarui).
     */
    public function generateSurat(Peminjaman $peminjaman): RedirectResponse
    {
        if (! in_array($peminjaman->status, ['disetujui', 'dipinjam', 'selesai'], true)) {
            return back()->with('error', 'Surat hanya dapat dibuat untuk peminjaman yang sudah disetujui.');
        }

        $this->suratService->generate($peminjaman);

        return redirect()->route('pengelola.peminjaman.show', $peminjaman)
            ->with('success', 'Surat pernyataan PDF berhasil dibuat ulang.');
    }

    /**
     * Pengelola menolak pengajuan peminjaman.
     */
    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if (! in_array($peminjaman->status, ['pending', 'diverifikasi'], true)) {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'alasan_penolakan' => ['required', 'string'],
        ]);

        $peminjaman->update([
            'status'            => 'ditolak',
            'alasan_penolakan'  => $request->alasan_penolakan,
            'ditolak_at'        => now(),
            'diverifikasi_oleh' => Auth::id(),
        ]);

        Notifikasi::create([
            'user_id'       => $peminjaman->peminjam_id,
            'peminjaman_id' => $peminjaman->id,
            'judul'         => 'Pengajuan Ditolak',
            'pesan'         => "Pengajuan peminjaman Anda (No. {$peminjaman->nomor_pengajuan}) ditolak oleh pengelola. Alasan: {$request->alasan_penolakan}",
            'tipe'          => 'persetujuan',
        ]);

        return redirect()->route('pengelola.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Pengelola mengkonfirmasi pengembalian barang dari peminjam.
     */
    public function konfirmasiKembali(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Status peminjaman tidak valid untuk konfirmasi pengembalian.');
        }

        $request->validate([
            'kondisi_saat_kembali' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'catatan'              => ['nullable', 'string'],
            'bukti_pengembalian'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $tanggalKembaliAktual = now()->toDateString();
        $terlambat            = now()->isAfter($peminjaman->tanggal_kembali);
        $hariTerlambat        = $terlambat
            ? $peminjaman->tanggal_kembali->diffInDays(now())
            : 0;

        $buktiPath = null;
        if ($request->hasFile('bukti_pengembalian')) {
            $buktiPath = $request->file('bukti_pengembalian')->store('pengembalian', 'public');
        }

        // Buat record pengembalian
        Pengembalian::create([
            'peminjaman_id'          => $peminjaman->id,
            'dikonfirmasi_oleh'      => Auth::id(),
            'tanggal_kembali_aktual' => $tanggalKembaliAktual,
            'kondisi_saat_kembali'   => $request->kondisi_saat_kembali,
            'catatan'                => $request->catatan,
            'bukti_pengembalian'     => $buktiPath,
            'terlambat'              => $terlambat,
            'hari_terlambat'         => $hariTerlambat,
        ]);

        // Update status peminjaman
        $peminjaman->update(['status' => 'selesai']);

        // Update status aset kembali tersedia
        $peminjaman->aset->update(['status' => 'tersedia']);

        // Kirim notifikasi ke peminjam
        Notifikasi::create([
            'user_id'       => $peminjaman->peminjam_id,
            'peminjaman_id' => $peminjaman->id,
            'judul'         => 'Pengembalian Dikonfirmasi',
            'pesan'         => "Pengembalian aset untuk peminjaman No. {$peminjaman->nomor_pengajuan} telah dikonfirmasi. Terima kasih.",
            'tipe'          => 'pengembalian',
        ]);

        return redirect()->route('pengelola.peminjaman.show', $peminjaman)
            ->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }

    /**
     * Pengelola mengubah status aset ke "dipinjam" setelah barang diambil.
     */
    public function konfirmasiAmbil(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Status peminjaman tidak valid untuk konfirmasi pengambilan.');
        }

        $peminjaman->update(['status' => 'dipinjam']);
        $peminjaman->aset->update(['status' => 'dipinjam']);

        Notifikasi::create([
            'user_id'       => $peminjaman->peminjam_id,
            'peminjaman_id' => $peminjaman->id,
            'judul'         => 'Pengambilan Aset Dikonfirmasi',
            'pesan'         => "Pengambilan aset untuk peminjaman No. {$peminjaman->nomor_pengajuan} telah dikonfirmasi. Jaga barang dengan baik!",
            'tipe'          => 'pengembalian',
        ]);

        return redirect()->route('pengelola.peminjaman.show', $peminjaman)
            ->with('success', 'Pengambilan aset berhasil dikonfirmasi.');
    }
}
