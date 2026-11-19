<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Peminjaman;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman laporan umum.
     */
    public function index(): View
    {
        $akunDikecualikan = User::peminjam()->dikecualikanRekap()->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.laporan.index', compact('akunDikecualikan'));
    }

    /**
     * Export laporan (rekap bulanan) data peminjaman ke PDF.
     * Aktivitas peminjaman & pengembalian dari akun yang ditandai
     * "dikecualikan dari rekap" oleh admin TIDAK dihitung.
     */
    public function exportPeminjamanPdf(Request $request): Response
    {
        $request->validate([
            'status' => ['nullable', 'string'],
            'bulan'  => ['nullable', 'integer', 'between:1,12'],
            'tahun'  => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $filterPeriode = function ($query) use ($request) {
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('bulan')) {
                $query->whereMonth('created_at', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('created_at', $request->tahun);
            }
            return $query;
        };

        $peminjamans = $filterPeriode(
            Peminjaman::with(['peminjam', 'aset', 'pengembalian'])->masukRekap()->latest()
        )->get();

        // Hanya jumlahnya — sebagai catatan di laporan, datanya tidak ditampilkan
        $jumlahDikecualikan = $filterPeriode(Peminjaman::dikecualikanRekap())->count();

        $filter = $request->only('status', 'bulan', 'tahun');
        if (! empty($filter['bulan'])) {
            $filter['nama_bulan'] = \Carbon\Carbon::create(null, (int) $filter['bulan'], 1)
                ->locale('id')->translatedFormat('F');
        }

        $pdf = Pdf::loadView('pdf.laporan-peminjaman', [
            'peminjamans'        => $peminjamans,
            'jumlahDikecualikan' => $jumlahDikecualikan,
            'filter'             => $filter,
            'tanggal'            => now()->locale('id')->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        $periode = ! empty($filter['bulan'])
            ? sprintf('%04d-%02d', $filter['tahun'] ?? now()->year, $filter['bulan'])
            : now()->format('Y-m-d');

        return $pdf->download("rekap-peminjaman-{$periode}.pdf");
    }

    /**
     * Export laporan data aset ke PDF.
     */
    public function exportAsetPdf(Request $request): Response
    {
        $asets = Aset::with('kategori')->get();

        $pdf = Pdf::loadView('pdf.laporan-aset', [
            'asets'    => $asets,
            'tanggal'  => now()->locale('id')->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-aset-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export data peminjam ke Excel.
     */
    public function exportPeminjamExcel(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new \App\Exports\PeminjamExport(), 'data-peminjam-' . now()->format('Y-m-d') . '.xlsx');
    }
}
